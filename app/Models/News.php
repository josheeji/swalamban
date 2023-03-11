<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class News extends Model implements Searchable
{
    use Sluggable;
    use ModelEventLogger;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'display_order',
        'excerpt',
        'title',
        'slug',
        'image',
        'description',
        'published_date',
        'is_active',
        'created_by',
        'updated_by',
        'status_by',
        'deleted_by',
        'layout',
        'existing_record_id',
        'language_id',
        'type',
        'visible_in',
        'banner',
        'show_in_notification',
        'category_id',
        'show_image',
        'document'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = [
        'created_at',
        'published_date'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = $this->category ? url('news/' . $this->category->slug . '/' . $this->slug) : url('news/' . $this->slug);

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->type) && is_array($model->type)) {
                $model->type = implode(',', $model->type);
            }
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $news = News::find($model->existing_record_id);
                $model->slug = $model->existingRecord->slug;
                $model->display_order = $news->display_order;
                $model->is_active = $news->is_active;
                $model->category_id = self::multiCategory($news->category_id, $news->language_id);
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->type) && is_array($model->type)) {
                $model->type = implode(',', $model->type);
            }
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $news = News::find($model->existing_record_id);
                $model->display_order = $news->display_order;
                $model->category_id = self::multiCategory($news->category_id, $model->language_id);
                $model->is_active = $news->is_active;
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
        return $this->belongsTo(News::class, 'existing_record_id');
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
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    public static function multiCategory($category_id, $language_id)
    {
        if ($category_id == null) {
            return $category_id;
        }
        if ($parent = NewsCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
        }
        return $category_id;
    }
}
