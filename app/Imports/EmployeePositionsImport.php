<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\EmployeePosition;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');

class EmployeePositionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = $row['designation'] ?? null;
        EmployeePosition::create([
            'positionId' => Str::orderedUuid(),
            'name' => $name,
        ]);
    }
    
    public function headingRow(): int
    {
        return 1;
    }
}
