<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Itinerary extends Model
{
    use SoftDeletes;

    protected $table = 'itineraries';

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
        "package_id",
        'slug',
        'image',
        'short_description',
        'description',
        'is_active'
    ];
}
