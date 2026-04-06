<?php
namespace App\Exports;

use App\Models\Category;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{

    public function __construct()
    {
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        return Category::orderBy('created_at', 'asc');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        $type = '';
        switch($item->type){
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

        return [
            $item->title_en,
            $item->id,
            $item->title,
            $item->description_en,
            $item->description,
            $item->hero_title_en,
            $item->hero_description_en,
            $item->hero_description,
            $item->hero_title,
            $type,
            ($item->status == 1) ? true : false,
            $item->getImageAttribute(),
            $item->getImageENAttribute(),
            $item->getHeroImageAttribute(),
            $item->getHeroENAttribute(),
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            'Display Name',
            'WebsIte Category ID',
            'Arabic Title',
            'Description',
            'Arabic Description',
            'Hero Title',
            'Hero Description',
            'Arabic Hero Description',
            'Arabic Hero Title',
            'Project Type',
            'Active',
            'Arabic Image',
            'English Image',
            'Arabic Hero Image',
            'English Hero Image'
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
