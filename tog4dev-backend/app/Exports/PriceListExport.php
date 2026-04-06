<?php
namespace App\Exports;

use App\Models\ItemPrice;
use App\Models\QuickContributionPrice;
use App\Helpers\Helper;
use App\Http\Resources\Api\V2\ItemResource;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PriceListExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{
    protected $type;

    public function __construct($type = null)
    {
        $this->type = $type;
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        if($this->type == "quick"){
            return QuickContributionPrice::whereHas('quickContribution', function ($query) {
                $query->whereNull('deleted_at');
            })->orderBy('quick_contribution_id', 'asc');
        } else {
            return ItemPrice::orderBy('item_id', 'asc');
        }
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        if($this->type == "quick"){
            return [
                $item->price,
                $item->price_usd,
                $item->id,
                $item->quickContribution->id,
                $item->quickContribution->odoo_id,
            ];
        } else {
            return [
                $item->price,
                $item->price_en,
                $item->id,
                $item->item->id,
                $item->item->odoo_id,
            ];
        }
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            'Price',
            'Price USD',
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
