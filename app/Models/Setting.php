<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected  $table = 'site_settings';

    protected $fillable = [
        'name', 'value', 'slug', 'is_active', 'type', 'description'
    ];
}
