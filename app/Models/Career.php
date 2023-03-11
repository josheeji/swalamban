<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Career extends Model
{
    use Sluggable;
    use ModelEventLogger;

    protected $table = 'careers';

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'title',
        'slug',
        'location',
        'description',
        'publish_from',
        'publish_to',
        'display_order',
        'is_active',
        'file',
        'opening'
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
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $career = Career::find($model->existing_record_id);
                $model->slug = $model->existingRecord->slug;
                $model->display_order = $career->display_order;
                $model->is_active = $career->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $career = Career::find($model->existing_record_id);
                $model->display_order = $career->display_order;
                $model->is_active = $career->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function existingRecord()
    {
        return $this->belongsTo(Career::class, 'existing_record_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }
}
