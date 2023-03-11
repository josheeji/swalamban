<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryVideo extends Model
{
    use SoftDeletes;
    use ModelEventLogger;
    use Sluggable;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'display_order',
        'title',
        'slug',
        'image',
        'link',
        'is_active',
        'created_by',
        'updated_by',
        'status_by',
        'deleted_by',
        'existing_record_id',
        'language_id'
    ];
}
