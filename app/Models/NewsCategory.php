<?php

namespace App\Models;

use App\Helper\Helper;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsCategory extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected  $table = 'news_categories';

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'parent_id',
        'title',
        'slug',
        'display_order',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function existingRecord()
    {
        return $this->belongsTo(NewsCategory::class, 'existing_record_id');
    }

    public function parent()
    {
        return $this->belongsTo(NewsCategory::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function allChild()
    {
        return $this->hasMany(NewsCategory::class, 'parent_id')->with('child');
    }

    public function child()
    {
        return $this->hasMany(NewsCategory::class, 'parent_id')->where('is_active', '1')->orderBy('display_order', 'asc');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'category_id')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->orderBy('published_date', 'desc');
    }
    public function newsHomepage()
    {
        return $this->hasMany(News::class, 'category_id')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->orderBy('published_date', 'desc');
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // $model->created_by = auth()->user()->id;
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
}
