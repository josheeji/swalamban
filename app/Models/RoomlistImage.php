<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomlistImage extends Model
{

    protected $table = 'roomlistimage';

    protected $fillable = ['roomlist_id','image','is_active','description'];
}
