<?php
namespace App\Exports;

use App\Models\CollectionTeam;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CollectionTeamExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithChunkReading
{

    public function __construct()
    {
    }

    /**
     * Query to get payments
     */
    public function query()
    {
        return CollectionTeam::orderBy('created_at', 'asc');;
    }

    /**
     * Map each row
     */
    public function map($item): array
    {
        $items_name = [];

        foreach ($item->cartItems as $key => $item2) {
            if($item2->model_type == "App\Models\Item"){
                $category_type = $item2->item->category->type ?? '';
            } else {
                $category_type = $item2->quickContribute->category->type ?? '';   
            }
            $title = $item2->title_en ?? '';
            $category_name = Helper::getFlipTypes($category_type);
            $items_name[] = ($key + 1) . ") " . $title . " ; (" . $item2->price . __('app.currency') . ") " . $category_name . " - (" . $item2->type . ")";
        }

        return [
            $item->id,
            $item->first_name,
            $item->last_name,
            $item->email,
            $item->phone,
            $item->city,
            $item->address,
            $item->created_at,
            implode(" ---- ", $items_name),
        ];
    }
    
    /**
     * Define the headings
     */
    public function headings(): array
    {										
        return [
            '#',
            'First Name',
            'Last Name',
            'E-mail',
            'Phone',
            'City',
            'Address',
            'Created at',
            'Items'
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
