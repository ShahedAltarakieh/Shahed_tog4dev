<?php
namespace App\Exports;

use App\Models\Payment;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PaymentsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{

    protected $start_date;
    protected $end_date;

    public function __construct($start = null, $end = null)
    {
        $this->start_date = $start;
        $this->end_date = $end;
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        if($this->start_date && $this->end_date){
            return Payment::with('user', 'cartItems', 'influencer')
                ->where('status', 'approved')
                ->whereDate('created_at', '>=', $this->start_date)
                ->whereDate('created_at', '<=', $this->end_date)
                ->orderBy('created_at', 'desc');
        } else {
            return Payment::with('user', 'cartItems', 'influencer')
                ->where('status', 'approved')
                ->where('not_send_email', $this->only_2024)
                ->orderBy('created_at', 'desc');
        }
    }

    /**
     * Map each row
     */
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
            $items_name[] = ($key + 1) . ") " . $title . " ; (" . $item2->price . " JOD) (". $item2->location_en . ") (". $item2->quantity .") " . $category_name . " - (" . $item2->type . ")";
        }

        return [
            $item->id,
            optional($item->userDetails)->first_name . " " . optional($item->userDetails)->last_name,
            optional($item->userDetails)->phone,
            optional($item->userDetails)->email,
            optional($item->userDetails)->country,
            $item->payment_type,
            $item->cart_id,
            $item->amount,
            "JOD",
            optional($item->created_at)->format('Y-m-d H:i:s'),
            ucfirst($item->status),
            implode(" ---- ", $items_name),
            optional($item->influencer)->name ?? 'Website',
        ];
    }

    // public function collection()
    // {
    //     $payments = collect();
    //     $payments_list = Payment::where('payment_type', 'cliq')->where('id', '>=' ,'8518')->get();

    //     foreach ($payments_list as $key => $payment) {
    //         $payment_item = ExcelOrders::where('order_id', $payment->tran_ref)->first();
    //         $payments->push([
    //             'Order ID' => $payment->tran_ref,
    //             'Date' => $payment->created_at->format('Y-m-d'),
    //             'Name' => $payment->userDetails->first_name." ".$payment->userDetails->last_name,
    //             'Email' => $payment->userDetails->email,
    //             'Number' => $payment_item->customer_phone_number,
    //             'Total' => $payment->amount,
    //             'Items' => $payment_item->order_items,
    //             'Contract Number' => $payment->contract_id,
    //         ]);
    //     }
    //     return $payments;
    // }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {
        return [
            '#',
            'Customer',
            'Phone',
            'E-mail',
            'Country',
            'Payment method',
            'Transaction ID',
            'Amount',
            'Currency',
            'Created At',
            'Status',
            'Items',
            'Influencer Name'
        ];
    }

    // public function headings(): array
    // {
    //     return [
    //         'Order ID',
    //         'Date',
    //         'Name',
    //         'Email',
    //         'Number',
    //         'Total',
    //         'Items',
    //         'Contract Number'
    //     ];
    // }

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
