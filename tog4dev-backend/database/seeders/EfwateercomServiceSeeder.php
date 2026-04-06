<?php

namespace Database\Seeders;

use App\Models\EfwateercomService;
use App\Models\Item;
use App\Models\QuickContribution;
use Illuminate\Database\Seeder;

class EfwateercomServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parent = null;

        $item = Item::first();
        if ($item) {
            $parent = EfwateercomService::updateOrCreate(
                ['service_type' => 'donation_item'],
                [
                    'parent_id' => null,
                    'model_type' => Item::class,
                    'model_id' => $item->id,
                    'price' => 25.50,
                ]
            );
        }

        $quick = QuickContribution::first();
        if ($quick) {
            EfwateercomService::updateOrCreate(
                ['service_type' => 'donation_quick'],
                [
                    'parent_id' => $parent !== null ? (string) $parent->id : null,
                    'model_type' => QuickContribution::class,
                    'model_id' => $quick->id,
                    'price' => 15.00,
                ]
            );
        }
    }
}
