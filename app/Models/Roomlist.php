<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomlistImage;

class Roomlist extends Model
{
    protected $table = 'roomlist';

    protected $fillable = [ 'id',
        'name',
        'description',
        'number_of_rooms',
        'cover_image',
        'short_description',
        'is_active',
        'created_by',
        'updated_by'
    ];
    public function roomimages()
    {
        return $this->hasMany(RoomlistImage::class,'roomlist_id');
    }
}
