<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Route;

class InternalWeb extends Model
{
    use SoftDeletes;
    use ModelEventLogger;
    use Sluggable;

    protected $fillable = [
        'type',
        'display_order',
        'title',
        'slug',
        'file',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'status_by',
        'deleted_by',
        'existing_record_id',
        'parent_id',
        'language_id',
        'category_id',
        'published_date',
        'year',
        'month'
    ];
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
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                $category = InternalWeb::find($model->existing_record_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->created_by = auth()->user()->id ?? null;
        });

        self::created(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                self::updateContent($model);
                self::updateDownload($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                $category = DownloadCategory::find($model->existing_record_id);
                $model->display_order = $category->display_orderr;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id ?? null;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                self::updateContent($model);
                self::updateDownload($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }
    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }
    public function parent()
    {
        return $this->belongsTo(InternalWeb::class, 'parent_id')->where('is_active', '1');
    }

    public function allChild()
    {
        return $this->hasMany(InternalWeb::class, 'parent_id')->with('child');
    }

    public function child()
    {
        return $this->hasMany(InternalWeb::class, 'parent_id')->where('is_active', '1')->orderBy('display_order', 'asc');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function category()
    {
        return $this->belongsTo(InternalWebCategory::class, 'category_id')->orderBy('display_order', 'asc');
    }
}
