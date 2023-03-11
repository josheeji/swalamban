<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class TeamCategory extends Model
{
    use SoftDeletes;
    use Sluggable;
    use ModelEventLogger;

    protected  $table = 'team_categories';

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

    public function existingRecord()
    {
        return $this->belongsTo(TeamCategory::class, 'existing_record_id');
    }

    public function parent()
    {
        return $this->belongsTo(TeamCategory::class, 'parent_id');
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
        return $this->hasMany(TeamCategory::class, 'parent_id')->with('child');
    }

    public function child()
    {
        return $this->hasMany(TeamCategory::class, 'parent_id')->where('is_active', '1')->orderBy('display_order', 'asc');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'category_id')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $category = TeamCategory::find($model->existing_record_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            if ($model->existing_record_id != null) {
                self::updateTeam($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $category = TeamCategory::find($model->existing_record_id);
                $model->display_order = $category->display_order;
                $model->is_active = $category->is_active;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                self::updateTeam($model);
            }
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

    protected static function updateTeam($model)
    {
        if ($category = TeamCategory::find($model->existing_record_id)) {
            if ($teams = Team::where('category_id', $category->id)->get()) {
                foreach ($teams as $team) {
                    if ($team->language_id == $model->language_id) {
                        $team->category_id = $model->id;
                        $team->save();
                    }
                }
            }
        }
    }
}
