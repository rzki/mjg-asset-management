<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'assetId';
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'pic_id');
    }
}
