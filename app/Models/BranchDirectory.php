<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class BranchDirectory extends Model implements Searchable
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

    public function getSearchResult(): SearchResult
    {
        $url = url($this->slug);

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    protected $fillable  = [
        'existing_record_id',
        'language_id',
        'title',
        'slug',
        'inside_valley',
        'type',
        'address',
        'ward_no',
        'district_id',
        'province_id',
        'lat',
        'long',
        'display_order',
        'is_active',
        'prefix',
        'fullname',
        'phone',
        'email',
        'fax',
        'designation',
        'url',
        'mobile',
        'is_headoffice',
        'photo'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $branch = BranchDirectory::find($model->existing_record_id);
                $model->slug = $model->existingRecord->slug;
                $model->province_id = self::multiProvince($branch->province_id, $model->language_id);
                $model->district_id = self::multiDistrict($branch->district_id, $model->language_id);
                $model->display_order = $branch->display_order;
                $model->is_active = $branch->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $branch = BranchDirectory::find($model->existing_record_id);
                $model->province_id = self::multiProvince($branch->province_id, $model->language_id);
                $model->district_id = self::multiDistrict($branch->district_id, $model->language_id);
                $model->display_order = $branch->display_order;
                $model->is_active = $branch->is_active;
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
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function existingRecord()
    {
        return $this->belongsTo(BranchDirectory::class, 'existing_record_id');
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

    public static function multiProvince($province_id, $language_id)
    {
        if ($province = Province::where('existing_record_id', $province_id)->where('language_id', $language_id)->first()) {
            return $province->id;
        }
        return $province_id;
    }

    public static function multiDistrict($district_id, $language_id)
    {
        if ($district = District::where('existing_record_id', $district_id)->where('language_id', $language_id)->first()) {
            return $district->id;
        }
        return $district_id;
    }
}