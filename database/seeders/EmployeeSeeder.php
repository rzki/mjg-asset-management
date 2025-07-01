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
            'user_id' => 2,
            'name' => 'Muh Ardyansyah',
            'initial' => 'ARD',
            'division_id' => 1,
            'position_id' => 1,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Febry Ulfa Rizaldo',
            'user_id' => 3,
            'initial' => 'FUR',
            'division_id' => 1,
            'position_id' => 2,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Rizky Dhani Ismail',
            'user_id' => 4,
            'initial' => 'RDI',
            'division_id' => 1,
            'position_id' => 3,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Yoga Saputra',
            'user_id' => 5,
            'initial' => 'YOG',
            'division_id' => 1,
            'position_id' => 4,
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Pantas Hutapea',
            'user_id' => 6,
            'initial' => 'PGH',
            'division_id' => 1,
            'position_id' => 4,
        ]);
    }
}
