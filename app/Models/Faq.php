<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Faq extends Model implements Searchable
{
    use ModelEventLogger;

    protected $fillable = [
        'faq_category_id',
        'question',
        'answer',
        'is_active',
        'existing_record_id',
        'created_by',
        'updated_by',
        'display_order',
        'language_id'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = url($this->question);

        return new SearchResult(
            $this,
            $this->question,
            $this->answer,
            $url
        );
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $faq = Faq::find($model->existing_record_id);
                $model->faq_category_id = self::multiCategory($faq->faq_category_id, $model->language_id);
                $model->display_order = $faq->display_order;
                $model->is_active = $faq->is_active;
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $faq = Faq::find($model->existing_record_id);
                $model->faq_category_id = self::multiCategory($faq->faq_category_id, $model->language_id);
                $model->display_order = $faq->display_order;
                $model->is_active = $faq->is_active;
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

    public function category()
    {
        return $this->belongsTo('App\Models\FaqCategory', 'faq_category_id');
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
        return $this->belongsTo(Faq::class, 'existing_record_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function multiCategory($category_id, $language_id)
    {
        if ($category = FaqCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }
}
