<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\ModelEventLogger;

class Activity extends Model
{
    use Sluggable;
    use ModelEventLogger;
    use SoftDeletes;

    protected $table = 'activities';

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'display_order',
        'is_active'
    ];


    public function seo()
    {
        return $this->morphOne('App\Models\Seo', 'seoable');
    }
}
