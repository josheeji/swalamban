<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use App\Helper\SettingHelper;
use App\Repositories\MenuRepository;
use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Spatie\SchemaOrg\MenuItem;

class MenuItems extends Model
{
    use Sluggable;
    use ModelEventLogger;

    protected  $table = 'menu_items';
    protected $fillable = [
        'title',
        'slug',
        'language_id',
        'module_id',
        'menu_id',
        'parent_id',
        'existing_record_id',
        'type',
        'is_external',
        'link_url',
        'link_active',
        'block',
        'link_target',
        'display_order',
        'is_active',
        'icon',
        'image',
        'reference_id'
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
            $model->language_id = $model->language_id == 0 ? 1 : $model->language_id;
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
                $model->icon = $model->existingRecord->icon;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->parent_id = self::getParent($model);
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
        });

        self::updating(function ($model) {
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
                $model->icon = $model->existingRecord->icon;
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->parent_id = self::getParent($model);
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            $actionList = self::getActionList();

            if (!in_array($action, $actionList) && $model->existing_record_id == null) {
                self::updateContent($model);
            }
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItems::class, 'parent_id');
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
        return $this->belongsTo(MenuItems::class, 'existing_record_id');
    }

    public static function multiContent($id)
    {
        $result = [];
        $result['multiContent'] = false;
        $result['multiId'] = '';
        $result['multiTitle'] = '';
        $result['is_new'] = false;
        if ($item = MenuItems::where('existing_record_id', $id)->where('is_active', 1)->first()) {
            $result['multiContent'] = true;
            $result['multiId'] = $item->id;
            $result['multiTitle'] = $item->title;
        } else {
            $item = MenuItems::where('id', $id)->first();
            if ($item->type == 1) {
                if ($menuItem = self::moduleContent($item->module->alias, $item->reference_id)) {
                    $result['multiId'] = $menuItem->id;
                    $result['multiContent'] = true;
                    $result['multiTitle'] = $menuItem->title;
                    $result['is_new'] = true;
                }
            }
        }
        return json_encode($result);
    }

    public static function getParent($model)
    {
        $isMenuItem = MenuItems::where('id', $model->existing_record_id)->get();
        if ($isMenuItem) {
            foreach ($isMenuItem as $item) {
                if ($item->parent_id == null) {
                    return null;
                }
                $parentMenuItem = MenuItems::where('existing_record_id', $item->parent_id)->first();
                if ($parentMenuItem) {
                    return $parentMenuItem->id;
                } else {
                    return $item->parent_id;
                }
            }
        }
    }

    public static function updateMenuItem()
    {
        $preferredLanguage = SettingHelper::setting('preferred_language') == null ? ConstantHelper::DEFAULT_LANGUAGE : SettingHelper::setting('preferred_language');
        $items = MenuItems::where('language_id', $preferredLanguage)->orderBy('display_order', 'asc')->get();
        if ($items) {
            foreach ($items as $item) {
                $multiItem = MenuItems::where('existing_record_id', $item->id)->first();
                if ($multiItem) {
                    if ($item->parent_id != null) {
                        $parentItem = MenuItems::where('id', $item->parent_id)->first();
                        if ($parentItem) {
                            $parentMultiItem = MenuItems::where('existing_record_id', $parentItem->id)->first();
                            if ($parentMultiItem) {
                                $multiItem->parent_id = $parentMultiItem->id;
                            }
                        } else {
                            $multiItem->parent_id = $item->parent_id;
                        }
                    } else {
                        $multiItem->parent_id = null;
                    }
                    $multiItem->save();
                }
            }
        }
    }

    public static function moduleContent($module,  $existing_record_id = null)
    {
        switch ($module) {
            case 'content':
                return Content::where("is_active", 1)->where('existing_record_id', '=', $existing_record_id)->first();
            case 'blog':
                return Blog::where('is_active', 1)->where('existing_record_id', '=', $existing_record_id)->first();
            case 'news':
                return News::where('is_active', 1)->where('existing_record_id', '=', $existing_record_id)->first();
            case 'service':
                return Post::where('is_active', 1)->where('existing_record_id', '=', $existing_record_id)->where('type', ConstantHelper::POST_TYPE_SERVICE)->first();
            case 'offer':
                return Post::where('is_active', 1)->where('existing_record_id', '=', $existing_record_id)->where('type', ConstantHelper::POST_TYPE_OFFER)->first();
            case 'account-type':
                return AccountType::where('is_active', 1)->where('existing_record_id', '=', $existing_record_id)->first();
        }
    }

    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    protected static function getActionList()
    {
        return ['sort'];
    }

    protected static function updateContent($model)
    {
        if ($contents = MenuItems::where('existing_record_id', $model->id)->get()) {
            foreach ($contents as $content) {
                $content->icon = $model->icon;
                $content->link_target = $model->link_target;
                $content->block = $model->block;
                $content->is_external = $model->is_external;
                $content->is_active = $model->is_active;
                $content->save();
            }
        }
    }
}