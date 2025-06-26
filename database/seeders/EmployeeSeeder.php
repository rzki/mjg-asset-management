<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Muh Ardyansyah',
            'initial' => 'ARD',
            'division_id' => 1,
            'position_id' => 1,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Febry Ulfa Rizaldo',
            'initial' => 'FUR',
            'division_id' => 1,
            'position_id' => 2,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Rizky Dhani Ismail',
            'initial' => 'RDI',
            'division_id' => 1,
            'position_id' => 3,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Yoga Saputra',
            'initial' => 'YOG',
            'division_id' => 1,
            'position_id' => 4,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Pantas Hutapea',
            'initial' => 'PGH',
            'division_id' => 1,
            'position_id' => 4,
        ]);
    }
}
