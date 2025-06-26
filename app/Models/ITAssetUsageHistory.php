<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITAssetUsageHistory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_asset_usage_histories';

    public function asset()
    {
        return $this->belongsTo(ITAsset::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
