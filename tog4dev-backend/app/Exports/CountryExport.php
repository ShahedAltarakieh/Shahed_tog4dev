<?php
namespace App\Exports;

use App\Models\Payment;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

class CountryExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    protected $start_date;
    protected $end_date;

    public function __construct($start, $end)
    {
        $this->start_date = $start;
        $this->end_date = $end;
    }

    /**
     * Query to get subscriptions
     */
    public function query()
    {
        return DB::table('payment_user_details')
            ->join('payments', 'payments.id', '=', 'payment_user_details.payment_id')
            ->where('payments.status', 'approved')
            ->whereBetween('payments.created_at', [$this->start_date, $this->end_date])
            ->select('payment_user_details.country', DB::raw('COUNT(DISTINCT payments.user_id) as user_count'), DB::raw('SUM(payments.amount) as total_amount'), DB::raw('COUNT(payments.id) as total_transactions'))
            ->groupBy('payment_user_details.country')
            ->orderByDesc('total_amount');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        return [
            ucfirst($item->country),
            $item->user_count,
            $item->total_transactions,
            $item->total_amount . __('app.currency'),
        ];
    }

    /**
     * Define the headings
     */
    public function headings(): array
    {
        return [
            'Country',
            '# of Users',
            '# of Transactions',
            'Amount'
        ];
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
