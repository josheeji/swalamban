<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'alias',
        'show_in_menu',
        'display_order'
    ];

     public function sluggable() : array
    {
        return [
            'alias' => [
                'source' => 'name'
            ]
        ];
    }
}
