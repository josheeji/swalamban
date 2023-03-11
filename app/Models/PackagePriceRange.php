<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePriceRange extends Model
{
    protected $table = 'price_ranges';

    protected $fillable = ['package_id','traveller_range','amount','is_active'];
}
