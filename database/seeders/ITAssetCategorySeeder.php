<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\ITAssetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ITAssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ITAssetCategory::insert([
            [
                'code' => '001',
                'name' => 'SERVER',
            ],
            [
                'code' => '002',
                'name' => 'LAPTOP',
            ],
            [
                'code' => '003',
                'name' => 'PC',
            ],
            [
                'code' => '004',
                'name' => 'ACCESSORIES',
            ],
            [
                'code' => '005',
                'name' => 'PRINTER',
            ],
            [
                'code' => '006',
                'name' => 'SCANNER',
            ],

        ]);
    }
}
