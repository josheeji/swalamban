<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\ModelEventLogger;

class ImageResize extends Model
{
    use Sluggable, ModelEventLogger, SoftDeletes;

    protected $table = 'image_resize';

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'alias',
        'view_port_width',
        'view_port_height',
        'boundary_width',
        'boundary_height',
        'image_resize_width',
        'image_resize_height',
    ];
}
