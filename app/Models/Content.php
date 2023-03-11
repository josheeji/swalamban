<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Content extends Model implements Searchable
{
    use Sluggable;
    use ModelEventLogger;
    use SoftDeletes;

    protected $table = 'contents';

    protected $fillable = [
        'existing_record_id', 'is_show_member_link', 'language_id', 'meta_keys', 'meta_desc', 'parent_id', 'title', 'slug', 'image', 'banner', 'excerpt', 'description', 'display_order', 'status', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by', 'edit', 'layout', 'link', 'link_target', 'show_children', 'show_image', 'publish_at'
    ];

    public function sluggable(): array
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

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $content = $model->existingRecord;
                $model->slug = $content->slug;
                $model->parent_id = self::multiParent($content->parent_id, $model->language_id);
                $model->display_order = $content->display_order;
                $model->is_active = $content->is_active;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                self::updateMenuItem($model);
                self::updateContent($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            $actionList = self::getActionList();
            if (isset($model->existing_record_id) && !empty($model->existing_record_id) && !in_array($action, $actionList)) {
                $content = $model->existingRecord;
                $model->parent_id = self::multiParent($content->parent_id, $model->language_id);
                $model->display_order = $content->display_order;
                $model->is_active = $content->is_active;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            $actionList = self::getActionList();
            if (isset($model->existing_record_id) && !empty($model->existing_record_id) && !in_array($action, $actionList)) {
                self::updateMenuItem($model);
                self::updateContent($model);
            }
            if (!in_array($action, $actionList)) {
                self::updateTitle($model);
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
        return $this->belongsTo(Content::class, 'existing_record_id');
    }

    public function parent()
    {
        return $this->belongsTo(Content::class, 'parent_id')->where('is_active', '1')->where('language_id', 1);
    }

    public function child()
    {
        return $this->hasMany(Content::class, 'parent_id')->where('is_active', '1')->where('language_id', Helper::locale())->orderBy('display_order', 'asc');
    }

    public function allChild()
    {
        return $this->hasMany(Content::class, 'parent_id')->with('child')->where('is_active', '1');
    }

    public function getCreatedAt()
    {
        return $this->created_at->toFormattedDateString();
    }

    public function getUpdatedAt()
    {
        return $this->updated_at->toFormattedDateString();
    }

    public function getParentTitle()
    {
        return $this->parent ? $this->parent->title : "";
    }

    public static function updateMenuItem($model)
    {
        $isMenuItem = MenuItems::where('module_id', 8)
            ->where('reference_id', $model->existing_record_id)
            ->where('type', ConstantHelper::MENU_TYPE_CONTENT)->where('is_active', 1)->get();

        if ($isMenuItem) {
            foreach ($isMenuItem as $item) {
                $multiContent = MenuItems::where('reference_id', $model->id)->where('menu_id', $item->menu_id)->first();
                if ($multiContent) {
                }

                $parentMenuItem = MenuItems::where('existing_record_id', $item->parent_id)
                    ->where('language_id', $model->language_id)->first();
                $parentId = isset($parentMenuItem) ? $parentMenuItem->id : $item->parent_id;

                if ($checkMenuItem = MenuItems::where('module_id', $item->module_id)->where('existing_record_id',  $item->id)->first()) {
                    $menuItem = $checkMenuItem;
                } else {
                    $menuItem = new MenuItems();
                }
                $menuItem->module_id = $item->module_id;
                $menuItem->menu_id = $item->menu_id;
                $menuItem->type = $item->type;
                $menuItem->language_id = $model->language_id;
                $menuItem->title = $model->title;
                $menuItem->slug = $model->slug;
                $menuItem->display_order = $item->display_order;
                $menuItem->reference_id = $model->id;
                $menuItem->existing_record_id = $item->id;
                $menuItem->link_url = $item->link_url;
                $menuItem->icon = $item->icon;
                $menuItem->link_target = $item->link_target;
                $menuItem->parent_id = $parentId;
                if ($item->language_id == 1) {
                    $sort = $menuItem->save();
                }
                MenuItems::updateMenuItem();
            }
        }
    }

    public static function updateTitle($model)
    {
        $items = MenuItems::where('module_id', 8)
            ->where('reference_id', $model->id)
            ->where('type', ConstantHelper::MENU_TYPE_CONTENT)->get();
        if ($items) {
            foreach ($items as $item) {
                $url = explode('/', $model->link_url);
                array_pop($url);
                $url = implode('/', $url);
                $item->link_url = $url . '/' . $model->slug;
                $item->title = $model->title;
                $item->slug = $model->slug;
                $item->save();
            }
        }
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function getActionList()
    {
        return ['sort', 'destroyImage'];
    }

    protected static function multiParent($parent_id, $language_id)
    {
        if ($parent_id == null) {
            return $parent_id;
        }
        if ($parent = Content::where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
        }
        return $parent_id;
    }

    protected static function updateContent($model)
    {
        if ($contents = Content::where('parent_id', $model->existing_record_id)->get()) {
            foreach ($contents as $content) {
                if ($content->language_id == $model->language_id) {
                    $content->parent_id = $model->id;
                    $content->save();
                }
            }
        }
    }
}