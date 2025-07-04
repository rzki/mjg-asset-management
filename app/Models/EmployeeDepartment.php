<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    protected $guarded = ['id'];
    public function getRouteKeyName()
    {
        return 'departmentId';
    }
    public function division()
    {
        return $this->hasMany(EmployeeDivision::class, 'department_id');
    }
}
