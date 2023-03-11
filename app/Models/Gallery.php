<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Gallery extends Model
{
    use SoftDeletes;
    use ModelEventLogger;
    use Sluggable;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'display_order',
        'title',
        'slug',
        'image',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'status_by',
        'deleted_by',
        'existing_record_id',
        'language_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->type) && is_array($model->type)) {
                $model->type = implode(',', $model->type);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $gallery = Gallery::find($model->existing_record_id);
                $model->slug = $model->existingRecord->slug;
                $model->display_order = $gallery->display_order;
                $model->is_active = $gallery->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $gallery = Gallery::find($model->existing_record_id);
                $model->display_order = $gallery->display_order;
                $model->is_active = $gallery->is_active;
            }
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

    public function existingRecord()
    {
        return $this->belongsTo(Gallery::class, 'existing_record_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\GalleryImage', 'gallery_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }
}
