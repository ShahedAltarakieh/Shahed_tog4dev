<?php
namespace App\Exports;

use App\Models\Payment;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AllPaymentsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    protected float $totalAmount = 0;
    protected float $totalCartPrice = 0;

    /**
     * Query to get payments
     */
    public function query()
    {
        return Payment::withSum('cartItems', 'price')->where('status', 'approved')
            ->whereDate('created_at', '>=', "2025-04-01 00:00:00")
            ->whereDate('created_at', '<', "2025-07-01 00:00:00")
        ->orderBy('created_at', 'desc');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        // Accumulate totals while mapping
        $this->totalAmount += $item->amount;
        $this->totalCartPrice += $item->cart_items_sum_price;

        $is_diff = ($item->amount != $item->cart_items_sum_price) ? "Yes" : "No";
        return [
            $item->id,
            $item->cart_id,
            $item->contract_id,
            $item->amount,
            $item->cart_items_sum_price,
            $is_diff
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {
        return [
            '#',
            'Cart id',
            'Contract id',
            'Amount',
            'Cart price',
            'Diff Yes/No'
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headingCount = count($this->headings());

                // Bold header row
                $columnRange = 'A1:' . Coordinate::stringFromColumnIndex($headingCount) . '1';
                $sheet->getStyle($columnRange)->getFont()->setBold(true);

                // Get last data row
                $lastRow = $sheet->getHighestRow() + 1;

                // Write totals
                $sheet->setCellValue('D' . $lastRow, $this->totalAmount);
                $sheet->setCellValue('E' . $lastRow, $this->totalCartPrice);

                // Style total row
                $sheet->getStyle("D{$lastRow}:E{$lastRow}")->getFont()->setBold(true);
            },
        ];
    }


    /**
     * Set chunk size
     */
    public function chunkSize(): int
    {
        return 500; // or 1000 depending on your server memory
    }
}
