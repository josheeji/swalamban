<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class FinancialReport extends Model implements Searchable
{
    use SoftDeletes;
    use ModelEventLogger;

    protected $fillable = [
        'existing_record_id',
        'category_id',
        'language_id',
        'title',
        'file',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',
        'status_by',
        'published_date',
        'company_id'
    ];

    public function getSearchResult(): SearchResult
    {

        return new SearchResult(
            $this,
            $this->title
        );
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action == 'sort') {
                $financialReport = FinancialReport::find($model->existing_record_id);
                $model->category_id = self::multiCategory($financialReport->category_id, $model->language_id);
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action == 'sort') {
                $financialReport = FinancialReport::find($model->existing_record_id);
                $model->category_id = self::multiCategory($financialReport->category_id, $model->language_id);
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

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function category()
    {
        return $this->belongsTo(FinancialReportCategory::class, 'category_id');
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected function updateMultiCategory($category_id, $language_id)
    {
        if ($category = FinancialReportCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }
}