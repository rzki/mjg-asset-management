<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDivision extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'divisionId';
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
    public function positions()
    {
        return $this->hasMany(EmployeePosition::class, 'division_id');
    }
}
