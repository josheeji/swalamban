<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForexOrder extends Model
{
    protected $fillable = ['code', 'name', 'order'];
}
