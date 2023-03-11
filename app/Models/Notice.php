<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Notice extends Model implements Searchable
{
    use SoftDeletes;
    use ModelEventLogger;
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = ['display_order', 'position', 'title', 'slug', 'image', 'link', 'timer', 'start_date', 'end_date', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by', 'existing_record_id', 'language_id', 'excerpt', 'description', 'type', 'show_image', 'file'];

    protected $dates = ['start_date','end_date'];

    public function getSearchResult(): SearchResult
    {
        if ($this->type == 1) {
            $url = url('/notice');
        } elseif ($this->type == 2) {
            $url = url('/tender-notice');
        } elseif ($this->type == 3) {
            $url = url('/press-release');
        }

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
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $notice = Notice::find($model->existing_record_id);
                // $model->slug = $model->existingRecord->slug;
                $model->display_order = $notice->display_order;
                $model->is_active = $notice->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            // if (isset($model->existing_record_id) && $model->existing_record_id != null) {
            //     $model->slug = $model->existingRecord->slug;
            // }
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $notice = Notice::find($model->existing_record_id);
                $model->display_order = $notice->display_order;
                $model->is_active = $notice->is_active;
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

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }
}
