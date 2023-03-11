<?php

namespace App\Models;

use App\Helper\Helper;
use App\Repositories\PackageCategoryRepository;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'image',
        'excerpt',
        'is_active'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Helper::slug($model->title);
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $model->slug = Helper::slug($model->title);
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

    public function getParentTitle($id)
    {
        return $id == 0 ? 'Parent' : PackageCategory::where('id', $id)->value('title');
    }
}
