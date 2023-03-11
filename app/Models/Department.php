<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use ModelEventLogger;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'image',
        'excerpt',
        'description',
        'is_active',
        'display_order',
        'branch_id',
        'email',
        'phone',
        'fax',
        'language_id',
        'existing_record_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Helper::slug($model->title);
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $model->slug = Helper::slug($model->title);
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

    public function getParent($id)
    {
        return $id == 0 ? 'Parent' : Department::where('id', $id)->value('title');
    }

    public function branch()
    {
        return $this->BelongsTo(BranchDirectory::class, 'branch_id');
    }
}
