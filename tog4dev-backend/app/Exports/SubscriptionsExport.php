<?php
namespace App\Exports;

use App\Models\Subscription;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SubscriptionsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
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
        return Subscription::query()
            ->whereNotNull('end_date')
            ->whereBetween('created_at', [$this->start_date, $this->end_date]);
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        $title = $item->title_en;

        return [
            $item->id,
            $item->user->first_name." ".$item->user->last_name,
            (string) $item->user->phone,
            $item->user->email,
            $item->user->country,
 //           $item->payment->cart_id,
($item->payment) ? $item->payment->cart_id : $item->id, 
	    $title,
            $item->price . __('app.currency'),
            $item->start_date,
            $item->end_date,
            $item->influencer ? $item->influencer->name : 'Website',
            $item->status
        ];
    }

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
            'Transaction ID',
            'Item',
            'Amount',
            'Start Date',
            'Renew Date',
            'Influencer Name',
            'Status'
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
