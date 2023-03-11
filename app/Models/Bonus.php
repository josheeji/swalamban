<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonus extends Model
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $table = 'bonuses';
    protected $fillable = [
        'category_id', 'boid', 'name', 'searchable_name', 'type', 'actual_bonus', 'tax_amount', 'is_active', 'created_by', 'updated_by', 'shareholder_no', 'fathers_name', 'grandfathers_name', 'address', 'total', 'searchable_fathers_name', 'searchable_grandfathers_name'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->searchable_name = self::searchableName($model->name);
            $model->searchable_fathers_name = self::searchableName($model->fathers_name);
            $model->searchable_grandfathers_name = self::searchableName($model->grandfathers_name);
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
        });

        self::updating(function ($model) {
            $model->searchable_name = self::searchableName($model->name);
            $model->searchable_fathers_name = self::searchableName($model->fathers_name);
            $model->searchable_grandfathers_name = self::searchableName($model->grandfathers_name);
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

    protected static function searchableName($name)
    {
        return strtoupper(str_replace(' ', '', $name));
    }

    public function category()
    {
        return $this->belongsTo(BonusCategory::class, 'category_id');
    }
}
