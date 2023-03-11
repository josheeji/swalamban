<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class LayoutOption extends Model
{
    // use Sluggable;

    protected $fillable = [
        'language_id',
        'existing_record_id',
        'title',
        'slug',
        'layout_id',
        'excerpt',
        'type',
        'menu_id',
        'content_id',
        'value',
        'block_title',
        'subtitle',
        'image',
        'link',
        'link_text',
        'link_target',
        'external_link',
        'created_by',
        'updated_by'
    ];

    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'title'
    //         ]
    //     ];
    // }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $option = $model->existingRecord;
                $model->title = $option->title;
                $model->slug = $option->slug;
                $model->type = $option->type;
                $model->layout_id = $option->layout_id;
                $model->menu_id = $option->menu_id;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $option = $model->existingRecord;
                $model->title = $option->title;
                $model->slug = $option->slug;
                $model->type = $option->type;
                $model->layout_id = $option->layout_id;
                $model->menu_id = $option->menu_id;
                $model->content_id = $model->existingRecord->content_id;
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

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function existingRecord()
    {
        return $this->belongsTo(LayoutOption::class, 'existing_record_id');
    }
}
