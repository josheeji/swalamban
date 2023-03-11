<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Remittance extends Model
{
    use Sluggable;
    use ModelEventLogger;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable  = [
        'existing_record_id',
        'language_id',
        'title',
        'slug',
        'address',
        'contact_no',
        'relationship_officer',
        'country_id',
        'district_id',
        'province_id',
        'display_order',
        'is_active',
        'visible_in',
        'parent_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $remittance = Remittance::find($model->existing_record_id);
                $model->parent_id = self::multiParent($remittance->id, $model->language_id);
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $model->slug = $model->existingRecord->slug;
            }
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $remittance = Remittance::find($model->existing_record_id);
                $model->parent_id = self::multiParent($remittance->id, $model->language_id);
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $model->slug = $model->existingRecord->slug;
            }
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                self::updateParent($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function parent()
    {
        return $this->belongsTo(Remittance::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function multiParent($parent_id, $language_id)
    {
        if ($parent_id == null) {
            return $parent_id;
        }
        if ($parent = Remittance::where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
        }
        return $parent_id;
    }

    protected static function updateParent($model)
    {
        if ($remittances = Remittance::where('parent_id', $model->existing_record_id)->get()) {
            foreach ($remittances as $remittance) {
                if ($remittance->language_id == $model->language_id) {
                    $remittance->parent_id = $model->id;
                    $remittance->save();
                }
            }
        }
    }
}
