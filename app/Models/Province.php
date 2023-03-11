<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'title'
    ];

    public function district()
    {
        return $this->hasMany(District::class, 'province_id');
    }
}
