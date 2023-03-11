<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class FinancialReportCategory extends Model
{
    use ModelEventLogger;
    use Sluggable;

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'parent_id',
        'title',
        'slug',
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
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $category = FinancialReportCategory::find($model->existing_record_id);
                $model->parent_id = self::multiParent($category->parent_id, $model->language_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                self::updateCategory($model);
                self::updateFinancialReport($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $category = FinancialReportCategory::find($model->existing_record_id);
                $model->parent_id = self::multiParent($category->parent_id, $model->language_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                self::updateCategory($model);
                self::updateFinancialReport($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function existingRecord()
    {
        return $this->belongsTo(FinancialReportCategory::class, 'existing_record_id');
    }

    public function parent()
    {
        return $this->belongsTo(FinancialReportCategory::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function allChild()
    {
        return $this->hasMany(FinancialReportCategory::class, 'parent_id')->with('child');
    }

    public function child()
    {
        return $this->hasMany(FinancialReportCategory::class, 'parent_id')->where('language_id', Helper::locale())->where('is_active', '1');
    }

    public function reports()
    {
        return $this->hasMany(FinancialReport::class, 'category_id')->where('is_active', 1)->where('language_id', Helper::locale());
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
        if ($parent = FinancialReportCategory::where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
        }
        return $parent_id;
    }

    protected static function updateCategory($model)
    {
        if ($categories = FinancialReportCategory::where('parent_id', $model->existing_record_id)->get()) {
            foreach ($categories as $category) {
                if ($category->language_id == $model->language_id) {
                    $category->parent_id = $model->id;
                    $category->save();
                }
            }
        }
    }

    protected static function updateFinancialReport($model)
    {
        if ($category = FinancialReportCategory::find($model->existing_record_id)) {
            if ($reports = FinancialReport::where('category_id', $category->id)->get()) {
                foreach ($reports as $report) {
                    if ($report->language_id == $model->language_id) {
                        $report->category_id = $model->id;
                        $report->save();
                    }
                }
            }
        }
    }
}
