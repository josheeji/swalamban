<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class AccountType extends Model implements Searchable
{
    use SoftDeletes;
    use Sluggable;
    use ModelEventLogger;

    protected $table = 'account_types';

    protected $fillable = [
        'existing_record_id',
        'language_id',
        'parent_id',
        'title',
        'slug',
        'banner',
        'image',
        'excerpt',
        'feature',
        'description',
        'terms_and_conditions',
        'faq',
        'interest_rate',
        'minimum_balance',
        'interest_payment',
        'link',
        'link_text',
        'layout',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',
        'visible_in',
        'is_featured',
        'download_id',
        'show_image',
        'type',
        'category_id'
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
        // $url = url('/products/'.$this->category->slug.'/' . $this->slug);
        $url = url('/products/'.$this->slug);

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
            if (isset($model->visible_in) && is_array($model->visible_in)) {
                $model->visible_in = implode(',', $model->visible_in);
            }
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
                $model->download_id = $model->existingRecord->download_id;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $accountType = AccountType::find($model->existing_record_id);
                $model->visible_in = $accountType->visible_in;
                $model->display_order = $accountType->display_order;
                $model->is_active = $accountType->is_active;
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
            if (isset($model->existing_record_id) && !empty($model->existing_record_id)) {
                $model->slug = $model->existingRecord->slug;
                $model->download_id = $model->existingRecord->download_id;
            }
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $accountType = AccountType::find($model->existing_record_id);
                $model->visible_in = $accountType->visible_in;
                $model->display_order = $accountType->display_order;
                $model->is_active = $accountType->is_active;
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
            $model->updated_by = auth()->user()->id;
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function existingRecord()
    {
        return $this->belongsTo(AccountType::class, 'existing_record_id');
    }

    public function download()
    {
        return $this->belongsTo(Download::class, 'download_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function Category()
    {
        return $this->belongsTo(AccountTypeCategory::class, 'category_id')->where('language_id', Helper::locale());
    }
    protected static function getAction()
    {
        $action = explode('@', Route::getCurrentRoute()->getActionName());
        return is_array($action) && isset($action[1]) ? $action[1] : '';
    }

    public static function updateMenuItem($model)
    {
        $isMenuItem = MenuItems::where('module_id', 43)
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
        $items = MenuItems::where('module_id', 76)
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

    public function leads()
    {
        return $this->hasMany(ProductEnquiry::class, 'account_type_id');
    }

    public static function multiCategory($category_id, $language_id)
    {
        if ($category_id == null) {
            return $category_id;
        }
        if ($parent = AccountTypeCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
        }
        return $category_id;
    }
}
