<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use Sluggable;
    use ModelEventLogger;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'type',
        'title',
        'slug',
        'parent_id',
        'category_id',
        'image',
        'excerpt',
        'description',
        'display_order',
        'is_active'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }
    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }
}
