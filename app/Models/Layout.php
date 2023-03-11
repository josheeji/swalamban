<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'display_order',
        'is_active',
        'created_by',
        'updated_by'
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

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }
}
