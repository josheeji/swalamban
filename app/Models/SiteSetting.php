<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{

    protected  $table = 'site_settings';

    protected $fillable = [
        'title', 'description', 'name', 'value', 'key', 'slug', 'publish', 'type'
    ];
}
