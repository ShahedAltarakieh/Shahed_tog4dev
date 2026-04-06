<?php
namespace App\Exports;

use App\Models\Item;
use App\Helpers\Helper;
use App\Http\Resources\Api\V2\ItemResource;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ItemsExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{

    public function __construct()
    {
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        return Item::orderBy('created_at', 'asc');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        $type = '';
        switch($item->category->type){
            case 1:
                $type = "Organization";
            break;
            case 2:
                $type = "Individual Project";
            break;
            case 3:
                $type = "Crowd Funding";
            break;
            case 4:
                $type = "Home";
            break;
        }

        $payment_type = '';
        switch($item->payment_type){
            case 'One-Time':
                $payment_type = "One time";
            break;
            case 'Subscription':
                $payment_type = "Subscription";
            break;
            case 'Both':
                $payment_type = "Both";
            break;
        }

        return [
            $item->title_en ?? '',
            $item->id,
            $item->category->id,
            $item->title ?? '',
            $item->category_id,
            $item->category->title_en ?? '',
            isset($item->category) ? '"'.$item->category->type .'"' : '',
            $item->payment_type ?? '',
            $item->description_en ?? '',
            $item->description ?? '',
            $type,
            $item->category->odoo_id,
            $item->description_en ?? '',
            $item->amount ?? '',
            $item->amount_usd ?? '',
            $item->location_en ?? '',
            $item->location ?? '',
            $item->payment_type ?? '',
            $item->additionalInfo->project_story_en ?? '',
            $item->additionalInfo->project_story ?? '',
            $item->additionalInfo->bold_description_en ?? '',
            $item->additionalInfo->bold_description ?? '',
            $item->additionalInfo->normal_description ?? '',
            $item->additionalInfo->normal_description_en ?? '',
            $item->getImageENAttribute(),
            $item->getImageAttribute(),
            ($item->status == 1) ? true : false,
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            'Name',
            'Site Integration ID',
            'Website Category ID',
            'Arabic Title',
            'Arabic Description',
            'Project Type',
            'Category / ID',
            'Description',
            'Price',
            'Price USD',
            'Location',
            'Location Arabic',
            'Payment Type',
            'Project Story',
            'Arabic Project Story',
            'Bold Description',
            'Arabic Bold Description',
            'Arabic Normal Description',
            'Normal Description',
            'Image',
            'English Image',
            'Active'
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
