<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    // use Sluggable;

    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'page'
    //         ]
    //     ];
    // }

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'page',
        'meta_title',
        'meta_keywords',
        'meta_description',
        // 'slug',
        'deletable',
       'json_description'
    ];

    /**
     * Get all of the owning seoable models.
     */
    public function seoable()
    {
        return $this->morphTo();
    }

}
