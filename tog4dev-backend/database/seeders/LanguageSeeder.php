<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'code'        => 'en',
                'name'        => 'English',
                'native_name' => 'English',
                'direction'   => 'ltr',
                'is_default'  => true,
                'is_active'   => true,
                'position'    => 1,
            ],
            [
                'code'        => 'ar',
                'name'        => 'Arabic',
                'native_name' => 'العربية',
                'direction'   => 'rtl',
                'is_default'  => false,
                'is_active'   => true,
                'position'    => 2,
            ],
        ];

        foreach ($defaults as $row) {
            Language::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
