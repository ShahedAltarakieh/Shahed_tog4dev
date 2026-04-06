<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ForbiddenKeyword;

class ForbiddenKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywords = [
            'فااعل',
            'فاااععل',
            'فاااععلل',
            'فااعلل',
            'فااععل',
            'فاعل',
            'فععلل',
            'فاااعل',
            'فاااعلل',
            'فااالل',
            'فاعلل',
            'فاععل',
            'فاععلل',
            'ففااعل',
            'فاااااعععل',
            'فاااعللل',
            'فاععللل',
            'فااااعل',
            'فااااعللل',
            'فاعععلل',
            'فاااععللل',
            'فاععل',
            'فاااعععلل',
            'فاااااعل'
        ];

        foreach ($keywords as $keyword) {
            ForbiddenKeyword::firstOrCreate(['keyword' => $keyword]);
        }
    }
}
