<?php

namespace App\Exports;

use App\Models\ExcelOrders;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;

class PaymentsCliqExport implements FromCollection, WithHeadings, WithEvents
{
    protected $id;
    protected $type;

    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $payments = collect();

        if($this->type == "approved"){
            $data = ExcelOrders::where('excel_sheet_id', $this->id)->where('status', 'completed')->get();
            foreach ($data as $key => $order) {
                $payment = Payment::where('tran_ref', $order["order_id"])->first();
                $payments->push([
                    'Order ID' => $payment->tran_ref,
                    'Date' => $payment->created_at->format('Y-m-d'),
                    'Name' => $payment->userDetails->first_name." ".$payment->userDetails->last_name,
                    'Email' => $payment->userDetails->email,
                    'Number' => $order->customer_phone_number,
                    'Total' => $payment->amount,
                    'Items' => $order->order_items,
                    'Contract Number' => $payment->contract_id,
                ]);
            }
        } else {
            $data = ExcelOrders::where('excel_sheet_id', $this->id)->where('status', '<>', 'completed')->get();
            foreach ($data as $key => $order) {
                $payments->push([
                    'Order ID' => $order->order_id,
                    'Created At' => $order->created_order_at,
                    'Name' => $order->name,
                    'Email' => $order->customer_email,
                    'Number' => $order->customer_phone_number,
                    'Total' => $order->total,
                    'Order Items' => $order->order_items,
                    'Payment Method' => $order->payment_method,
                ]);
            }
        }
        return $payments;
    }

    /**
     * Add custom headers
     *
     * @return array
     */
    public function headings(): array
    {
        if($this->type == "approved"){
            return [
                'Order ID',
                'Date',
                'Name',
                'Email',
                'Number',
                'Total',
                'Items',
                'Contract Number'
            ];
        } else {
            return [
                'Order ID',
                'Created At',
                'Name',
                'Email',
                'Number',
                'Total',
                'Order Items',
                'Payment Method'
            ];
        }

    }

    /**
     * Apply styles to the headings
     *
     * @return array
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
}
