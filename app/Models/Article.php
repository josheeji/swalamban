<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Article extends Model
{
    use SoftDeletes, Sluggable;

    protected $table = 'articles';

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
        'url',
        'slug',
        'image',
        'short_description',
        'description',
        'is_active',
        'type',
        'display_order',
        'created_by',
        'updated_by',
        'status_by',
        'deleted_by'
    ];


    public function getCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('M d') : "";
    }

    public function getMonthCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('M') : "";
    }

    public function getDayCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('d') : "";
    }

    public function seo()
    {
        return $this->morphOne('App\Models\Seo', 'seoable');
    }
}
