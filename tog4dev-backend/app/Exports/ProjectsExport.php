<?php
namespace App\Exports;

use App\Models\Item;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProjectsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    protected $start_date;
    protected $end_date;
    protected $type;

    public function __construct($start, $end, $type)
    {
        $this->start_date = $start;
        $this->end_date = $end;
        $this->type = $type;
    }

    /**
     * Query to get subscriptions
     */
    public function query()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        return Item::filterByCategoryType($this->type)->with(['cartItemsPaid' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }]);
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        if($this->type == "crowdfunding"){
            $totalPayment = $item->cartItemsPaid->sum('price') ?? 0;
            $uniqueUsersCount = $item->cartItemsPaid->pluck('user_id')->unique()->count() ?? 0;
            $totalTransactions = $item->cartItemsPaid->count() ?? 0;
            $leftTargetRaw = ($totalPayment <= $item->amount) ? ($item->amount - $totalPayment) : 0;

            return [
                'item_name' => $item->title,
                'category' => $item->category->title ?? '',
                'amount' => $item->amount,
                'total_paid' => $totalPayment ?? 0,
                'is_closed' => ($totalPayment >= $item->amount) ? "Yes" : "No",
                'left_target' => floor($leftTargetRaw) != $leftTargetRaw ? number_format($leftTargetRaw, 3, ".", "") : $leftTargetRaw,
                'user_count' => $uniqueUsersCount,
                'total_transactions' => $totalTransactions,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        } else {
            $totalPayment = $item->cartItemsPaid->sum('price') ?? 0;
            $uniqueUsersCount = $item->cartItemsPaid->pluck('user_id')->unique()->count() ?? 0;
            $totalTransactions = $item->cartItemsPaid->count() ?? 0;

            return [
                'item_name' => $item->title,
                'category' => $item->category->title ?? '',
                'user_count' => $uniqueUsersCount ?? 0,
                'total_transactions' => $totalTransactions ?? 0,
                'total_payment' => $totalPayment . __('app.currency'),
            ];
        }
    }

    /**
     * Define the headings
     */
    public function headings(): array
    {
        if($this->type == "crowdfunding"){
            return [
                'Name',
                'Category',
                'Target',
                'Total Paid',
                'Is Closed',
                'Left Target',
                '# of Users',
                '# of Transactions',
                'Created at'
            ];
        } else {
            return [
                'Project',
                'Category',
                '# of Users',
                '# of Transactions',
                'Amount'
            ];
        }
    }

    /**
     * Style the headings
     */
    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $headingCount = count($this->headings());
                $columnRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($headingCount) . '1';
                $event->sheet->getDelegate()->getStyle($columnRange)->getFont()->setBold(true);
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
