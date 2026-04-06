<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Influencer;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithTitle;

class InfluencersExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    protected $start_date;
    protected $end_date;
    protected $data;

    public function __construct($start, $end)
    {
        $this->start_date = $start;
        $this->end_date = $end;

        // Prepare and sort data here
        $startDate = $this->start_date;
        $endDate = $this->end_date;

        $influencers = Influencer::with(['payments' => function ($query) use ($startDate, $endDate) {
            $query->where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate])
                ->with(['cartItems', 'subscriptions']);
        }])->get();

        $this->data = $influencers->map(function ($item) {
            $payments = $item->payments;

            $active_sum_sub = 0;
            $active_sum_total = 0;
            $inactive_sum_sub = 0;
            $inactive_sum_total = 0;
            $total_one_time_payments = 0;
            $total_monthly_payments = 0;

            foreach ($payments as $p) {
                $active_sum_sub += $p->subscriptions->where("status", "active")->count();
                $active_sum_total += $p->subscriptions->where("status", "active")->sum("price");
                $inactive_sum_sub += $p->subscriptions->where("status", "inactive")->count();
                $inactive_sum_total += $p->subscriptions->where("status", "inactive")->sum("price");
                $total_one_time_payments += $p->cartItems->where('type', 'one_time')->sum('price');
                $total_monthly_payments += $p->cartItems->where('type', 'monthly')->sum('price');
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'active_subscriptions' => $active_sum_sub,
                'active_subscription_total' => $active_sum_total,
                'inactive_subscriptions' => $inactive_sum_sub,
                'inactive_subscription_total' => $inactive_sum_total,
                'number_of_transactions' => $payments->count(),
                'one_time_total' => $total_one_time_payments,
                'subscription_total' => $total_monthly_payments,
                'total_amount' => $total_one_time_payments + $total_monthly_payments
            ];
        })->sortByDesc('total_amount')->values();
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        return [
            $item['id'],
            $item['name'],
            $item['active_subscriptions'],
            $item['active_subscription_total'].__('app.currency'),
            $item['inactive_subscriptions'],
            $item['inactive_subscription_total'].__('app.currency'),
            $item['number_of_transactions'],
            $item['one_time_total'].__('app.currency'),
            $item['subscription_total'].__('app.currency'),
            $item['total_amount'].__('app.currency'),
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Influencer Name',
            'Active Subscriptions',
            'Total Active Subscriptions',
            'Inactive Subscriptions',
            'Total Inactive Subscriptions',
            '# of Transactions',
            'Total One-Time Payment',
            'Total Subscription Payment',
            'Grand total payments',
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function ($event) {
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
}
