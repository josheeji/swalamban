<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    protected  $table = 'nav';

    protected $fillable = [
        'category_id',
        'value',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',
        'publish_at'
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

    public function category()
    {
        return $this->belongsTo(NavCategory::class, 'category_id');
    }
}
