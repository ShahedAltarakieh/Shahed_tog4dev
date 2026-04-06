<?php
namespace App\Exports;

use App\Models\Payment;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentMethodSheetsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading, WithTitle
{
    protected $start_date;
    protected $end_date;
    protected $paymentType;

    public function __construct($start, $end, $paymentType)
    {
        $this->start_date = $start;
        $this->end_date = $end;
        $this->paymentType = $paymentType;
    }

    public function query()
    {
        return Payment::with('user', 'cartItems', 'influencer')
            ->where('status', 'approved')
            ->where('payment_type', $this->paymentType)
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->orderBy('created_at', 'asc');
    }

    public function map($item): array
    {
        $items_name = [];

        foreach ($item->cartItems as $key => $item2) {
            if($item2->model_type == "App\Models\Item"){
                $category_type = $item2->item->category->type ?? '';
            } else {
                $category_type = $item2->quickContribute->category->type ?? '';   
            }
            $title = $item2->title_en ?? '';
            $category_name = Helper::getFlipTypes($category_type);
            $items_name[] = ($key + 1) . ") " . $title . " ; (" . $item2->location_en . ") (" . $item2->price . __('app.currency') . ") " . $category_name . " - (" . $item2->type . ")";
        }

        return [
            $item->id,
            optional($item->userDetails)->first_name . " " . optional($item->userDetails)->last_name,
            optional($item->userDetails)->phone,
            optional($item->userDetails)->email,
            optional($item->userDetails)->country,
            $item->cart_id,
            $item->amount . __('app.currency'),
            optional($item->created_at)->format('Y-m-d H:i:s'),
            implode(" ---- ", $items_name),
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Customer',
            'Phone',
            'E-mail',
            'Country',
            'Transaction ID',
            'Amount',
            'Created At',
            'Items'
        ];
    }

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

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return $this->paymentType; // Sheet name will be the payment method
    }
}
