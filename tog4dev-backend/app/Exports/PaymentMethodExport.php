<?php
namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PaymentMethodExport implements WithMultipleSheets
{
    protected $start_date;
    protected $end_date;

    public function __construct($start, $end)
    {
        $this->start_date = $start;
        $this->end_date = $end;
    }

    public function sheets(): array
    {
        $paymentMethods = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->select('payment_type')
            ->distinct()
            ->pluck('payment_type');

        $sheets = [];
        foreach ($paymentMethods as $method) {
            $sheets[] = new PaymentMethodSheetsExport($this->start_date, $this->end_date, $method);
        }

        return $sheets;
    }
}
