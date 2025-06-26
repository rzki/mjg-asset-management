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
        ITAssetCategory::create([
            'code' => '001',
            'name' => 'SERVER',
        ]);
        ITAssetCategory::create([
            'code' => '002',
            'name' => 'LAPTOP',
        ]);
        ITAssetCategory::create([
            'code' => '003',
            'name' => 'PC',
        ]);
        ITAssetCategory::create([
            'code' => '004',
            'name' => 'ACCESSORIES',
        ]);
    }
}
