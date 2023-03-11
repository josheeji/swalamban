<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Team extends Model implements Searchable
{
    use SoftDeletes;
    use ModelEventLogger;

    protected  $table = 'teams';

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'category_id',
        'full_name',
        'designation',
        'photo',
        'position',
        'description',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',
        'phone',
        'email',
        'date',
        'tenure'
    ];

    public function getSearchResult(): SearchResult
    {

        return new SearchResult(
            $this,
            $this->full_name
        );
    }

    public function existingRecord()
    {
        return $this->belongsTo(TeamCategory::class, 'existing_record_id');
    }

    public function category()
    {
        return $this->belongsTo(TeamCategory::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $team = Team::find($model->existing_record_id);
                $model->category_id = self::multiCategory($team->category_id, $model->language_id);
                $model->photo = $team->photo;
                $model->display_order = $team->display_order;
                $model->is_active = $team->is_active;
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $team = Team::find($model->existing_record_id);
                $model->category_id = self::multiCategory($team->category_id, $model->language_id);
                $model->photo = $team->photo;
                $model->display_order = $team->display_order;
                $model->is_active = $team->is_active;
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

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function multiCategory($category_id, $language_id)
    {
        if ($category = TeamCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }
}