<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Download extends Model implements Searchable
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $fillable = [
        'type',
        'display_order',
        'title',
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
    ];

    public function getSearchResult(): SearchResult
    {
        $url = url('/download');

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
        return $this->belongsTo(Download::class, 'parent_id')->where('is_active', '1');
    }

    public function allChild()
    {
        return $this->hasMany(Download::class, 'parent_id')->with('child');
    }

    public function child()
    {
        return $this->hasMany(Download::class, 'parent_id')->where('is_active', '1')->orderBy('display_order', 'asc');
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
        return $this->belongsTo(DownloadCategory::class, 'category_id')->orderBy('display_order', 'asc');
    }
}
