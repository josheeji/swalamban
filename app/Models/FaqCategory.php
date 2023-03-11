<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class FaqCategory extends Model
{
    use ModelEventLogger;
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'title',
        'is_active',
        'existing_record_id',
        'language_id',
        'created_by',
        'updated_by',
        'slug'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $category = FaqCategory::find($model->existing_record_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            if ($model->existing_record_id != null) {
                self::updateFaq($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $category = FaqCategory::find($model->existing_record_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                self::updateFaq($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function activeFaq()
    {
        return $this->hasMany('App\Models\Faq', 'faq_category_id')->where('language_id', '=', Helper::locale())->orderBy('display_order', 'asc')->where('is_active', 1);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function existingRecord()
    {
        return $this->belongsTo(FaqCategory::class, 'existing_record_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function updateFaq($model)
    {
        if ($category = FaqCategory::find($model->existing_record_id)) {
            if ($faqs = Faq::where('faq_category_id', $category->id)->get()) {
                foreach ($faqs as $faq) {
                    if ($faq->language_id == $model->language_id) {
                        $faq->faq_category_id = $model->id;
                        $faq->save();
                    }
                }
            }
        }
    }
}