<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryImage extends Model
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $fillable = [
        'gallery_id',
        'image',
        'is_active'
    ];
}
