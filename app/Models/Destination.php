<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;

class Destination extends Model
{
    use Sluggable, ModelEventLogger;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_order', 'name', 'meta_title', 'meta_description', 'slug', 'image', 'description', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function seo()
    {
        return $this->morphOne('App\Models\Seo', 'seoable');
    }

    public function activities()
    {
        return $this->hasMany('App\Models\Activity');
    }

    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'destination_id');
    }
}
