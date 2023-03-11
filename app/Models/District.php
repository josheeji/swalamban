<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use ModelEventLogger;

    protected $fillable = [
        'province_id',
        'title',
        'headquarter',
        'latitude',
        'longitude'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
