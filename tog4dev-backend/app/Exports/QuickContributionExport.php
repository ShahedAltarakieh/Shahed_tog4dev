<?php
namespace App\Exports;

use App\Models\QuickContribution;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class QuickContributionExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{

    public function __construct()
    {
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        return QuickContribution::orderBy('created_at', 'asc');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        $type = '';
        if($item->category){
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
        }

        return [
            $item->title_en,
            $item->title,
            $item->category->odoo_id,
            $item->category->id,
            $item->id,
            $item->description_en,
            $item->description,
            $item->location_en,
            $item->location,
            $item->target,
            $item->target_usd,
            $type,
            ($item->status == 1) ? true : false,
            $item->getImageAttribute(),
            $item->getImageENAttribute(),
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            'Name',
            'Arabic name',
            'Category/ ID',
            'Website Category ID',
            'Site Integration ID',
            'Description',
            'Arabic Description',
            'Location',
            'Arabic Location',
            'Price',
            'Price USD',
            'Project Type',
            'Active',
            'Image',
            'English Image',
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
