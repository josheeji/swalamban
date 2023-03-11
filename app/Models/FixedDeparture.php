<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class FixedDeparture extends Model
{
    use SoftDeletes;

    protected $table = 'fixed_departures';

    protected $fillable = [
        "package_id",
        "year",
        'departure_date',
        'return_date',
        'is_active'
    ];
}
