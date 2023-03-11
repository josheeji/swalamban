<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
// use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTypeCategory extends Model
{
    use SoftDeletes;
    use Sluggable;
    use HasFactory;

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'title',
        'slug',
        'banner',
        'image',
        'excerpt',
        'description',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',

    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function existingRecord()
    {
        return $this->belongsTo(AccountTypeCategory::class, 'existing_record_id');
    }
}