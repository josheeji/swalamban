<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Route;

class BlogCategory extends Model
{

    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'language_id', 'existing_record_id', 'slug', 'image', 'display_order', 'description', 'is_active', 'created_by', 'updated_by'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                $category = BlogCategory::find($model->existing_record_id);
                $model->slug = $category->slug;
            }
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                $category = BlogCategory::find($model->existing_record_id);
                $model->slug = $category->slug;
                self::updateBlog($model);
            }
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                $category = BlogCategory::find($model->existing_record_id);
                $model->slug = $category->slug;
            }
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != '' && $action != 'sort') {
                self::updateBlog($model);
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

    protected static function updateBlog($model)
    {
        if ($category = BlogCategory::find($model->existing_record_id)) {
            if ($blogs = Blog::where('category_id', $category->id)->get()) {
                foreach ($blogs as $blog) {
                    if ($blog->language_id == $model->language_id) {
                        $blog->category_id = $model->id;
                        $blog->save();
                    }
                }
            }
        }
    }
}
