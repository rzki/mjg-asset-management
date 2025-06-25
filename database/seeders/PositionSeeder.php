<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\EmployeePosition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeePosition::create([
            'positionId' => Str::orderedUuid(),
            'division_id' => 1,
            'name' => 'IT Manager',
        ]);
        EmployeePosition::create([
            'positionId' => Str::orderedUuid(),
            'division_id' => 1,
            'name' => 'IT Supervisor',
        ]);
        EmployeePosition::create([
            'positionId' => Str::orderedUuid(),
            'division_id' => 1,
            'name' => 'IT System',
        ]);
        EmployeePosition::create([
            'positionId' => Str::orderedUuid(),
            'division_id' => 1,
            'name' => 'IT Support',
        ]);
    }
}
