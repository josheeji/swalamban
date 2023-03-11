<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Banner extends Model
{
	use ModelEventLogger;

	protected $table = 'banners';

	protected $fillable = [
		'title_prefix',
		'title',
		'title_suffix',
		'caption',
		'description',
		'display_order',
		'image',
		'link_text',
		'link',
		'link_target',
		'type',
		'position',
		'layout',
		'is_active',
		'created_at',
		'updated_at',
		'created_by',
		'updated_by',
		'status_by',
		'deleted_by',
		'visible_in',
		'existing_record_id',
		'language_id',
		'show_block'
	];

	public static function boot()
	{
		parent::boot();

		self::creating(function ($model) {
			if (isset($model->visible_in) && is_array($model->visible_in)) {
				$model->visible_in = implode(',', $model->visible_in);
			}
			if (isset($model->existing_record_id) && $model->existing_record_id != null) {
				$banner = Banner::find($model->existing_record_id);
				$model->show_block = $banner->show_block;
				// $model->is_honeycomb = $banner->is_honeycomb;
				$model->display_order = $banner->display_order;
				$model->is_active = $banner->is_active;
			}
			$model->created_by = auth()->user()->id;
		});

		self::created(function ($model) {
			if (isset($model->existing_record_id) && $model->existing_record_id != null) {
				// self::updateItems($model);
			}
		});

		self::updating(function ($model) {
			if (isset($model->visible_in) && is_array($model->visible_in)) {
				$model->visible_in = implode(',', $model->visible_in);
			}
			if (isset($model->existing_record_id) && $model->existing_record_id != null) {
				$banner = Banner::find($model->existing_record_id);
				$model->show_block = $banner->show_block;
				// $model->is_honeycomb = $banner->is_honeycomb;
				$model->display_order = $banner->display_order;
				$model->is_active = $banner->is_active;
			}
			$model->updated_by = auth()->user()->id;
		});

		self::updated(function ($model) {
			$action = self::getAction();
			if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
				// self::updateItems($model);
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

	public function bannerItems()
	{
		return $this->hasMany(BannerItem::class)->where('is_active', '1')->where('language_id', Helper::locale())->orderBy('display_order', 'asc');
	}

	// protected static function updateItems($model)
	// {
	// 	if ($banner = Banner::find($model->existing_record_id)) {
	// 		if ($items = BannerItem::where('banner_id', $banner->id)->get()) {
	// 			foreach ($items as $item) {
	// 				if ($item->language_id == $model->language_id) {
	// 					$item->banner_id = $model->id;
	// 					$item->save();
	// 				}
	// 			}
	// 		}
	// 	}
	// }
}
