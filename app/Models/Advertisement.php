<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $fillable = [
        'placement_id',
        'image',
        'link',
        'visible_in',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
        });

        self::updating(function ($model) {
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
        });

        self::deleting(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class, 'placement_id');
    }
}
