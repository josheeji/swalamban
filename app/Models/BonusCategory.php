<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusCategory extends Model
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $table = 'bonus_categories';
    protected $fillable = [
        'title', 'is_active', 'created_by', 'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
        });

        self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    protected static function searchableName($name)
    {
        return strtoupper(str_replace(' ', '', $name));
    }
}
