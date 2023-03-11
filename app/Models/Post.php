<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Post extends Model
{
    use Sluggable;
    use ModelEventLogger;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'type',
        'title',
        'slug',
        'parent_id',
        'category_id',
        'banner',
        'image',
        'icon',
        'excerpt',
        'description',
        'display_order',
        'is_active',
        'existing_record_id',
        'banner_alt',
        'image_alt',
        'url',
        'link_target',
        'visible_in',
        'show_in_notification',
        'layout',
        'show_image',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
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
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $post = Post::find($model->existing_record_id);
                $model->display_order = $post->display_order;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                self::updateMenuItem($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id) && $action != 'sort') {
                $post = Post::find($model->existing_record_id);
                $model->display_order = $post->display_order;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if ($action != 'sort') {
                self::updateTitle($model);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id) && $action != 'sort') {
                self::updateMenuItem($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function existingRecord()
    {
        return $this->belongsTo(Post::class, 'existing_record_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public static function updateMenuItem($model)
    {
        $moduleId = 34;
        if ($model->type == 3) {
            $moduleId = 74;
        }
        $isMenuItem = MenuItems::where('module_id', $moduleId)
            ->where('reference_id', $model->existing_record_id)
            ->where('type', ConstantHelper::MENU_TYPE_CONTENT)->where('is_active', 1)->get();
        if ($isMenuItem) {
            foreach ($isMenuItem as $item) {
                $multiContent = MenuItems::where('reference_id', $model->id)->where('menu_id', $item->menu_id)->first();
                if ($multiContent) {
                    // continue;
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
                // if (isset($parentMenuItem)) {
                if ($item->language_id == 1)
                    $sort = $menuItem->save();
                // }
                MenuItems::updateMenuItem();
            }
        }
    }

    public static function updateTitle($model)
    {
        $moduleId = 34;
        if ($model->type == 3) {
            $moduleId = 74;
        }
        $items = MenuItems::where('module_id', $moduleId)
            ->where('reference_id', $model->id)
            ->where('type', ConstantHelper::MENU_TYPE_CONTENT)->get();
        if ($items) {
            foreach ($items as $item) {
                $url = explode('/', $model->link_url);
                array_pop($url);
                $url = implode('/', $url);
                if ($moduleId == 74) {
                    $url = $url . '/offers';
                }
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
}