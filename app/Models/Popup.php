<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popup extends Model
{
    use SoftDeletes;
    use ModelEventLogger;
    use Sluggable;

    protected $table = 'pop';

    protected $fillable = [
        'title',
        'slug',
        'show_title',
        'visible_in',
        'url',
        'is_active',
        'image',
        'description',
        'target',
        'btn_label',
        'show_button',
        'show_in_notification',
        'show_image'
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
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
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
