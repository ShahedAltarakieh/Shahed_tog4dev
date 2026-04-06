<?php
namespace App\Exports;

use App\Models\PriceOption;
use App\Models\QuickContributionPrice;
use App\Helpers\Helper;
use App\Http\Resources\Api\V2\ItemResource;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PriceOptionExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    /**
     * Query to get payments
     */
    public function query()
    {
        return PriceOption::orderBy('item_id', 'asc');
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        return [
            $item->d1_option_en,
            $item->d1_option,
            $item->d2_option_en,
            $item->d2_option,
            $item->price,
            $item->price_en,
            $item->is_default,
            $item->id,
            $item->item->id,
            $item->item->odoo_id,
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            'Title1',
            'Arabic Title1',
            'Title2',
            'Arabic Title2',
            'Price',
            'Price USD',
            'is default',
            'Website id',
            'Product website id',
            'Product odoo id',
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
