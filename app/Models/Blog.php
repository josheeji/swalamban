<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Route;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Blog extends Model implements Searchable
{
    use Sluggable;
    use ModelEventLogger;

    protected $guarded = ['id'];
    protected $dates = ['published_date'];

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

    public function getSearchResult(): SearchResult
    {
        $url = url('/blogs/' . $this->slug);

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'category_id', 'image', 'description', 'is_active', 'excerpt', 'existing_record_id', 'language_id', 'show_image', 'created_at', 'updated_at','published_date',
        'document','banner'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // $model->created_by = auth()->user()->id;
            if (isset($model->existing_record_id) && $model->existing_record_id != null) {
                $blog = Blog::find($model->existing_record_id);
                $model->slug = $blog->slug;
                $model->category_id = self::multiCategory($blog->category_id, $model->language_id);
                $model->image = $blog->image;
                $model->is_active = $blog->is_active;
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $action = self::getAction();
            if (isset($model->existing_record_id) && $model->existing_record_id != null && $action != 'sort') {
                $blog = Blog::find($model->existing_record_id);
                $model->slug = $blog->slug;
                $model->category_id = self::multiCategory($blog->category_id, $model->language_id);
                $model->image = $blog->image;
                $model->is_active = $blog->is_active;
            }
            // $model->updated_by = auth()->user()->id;
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

    /**
     * The categories that belong to the deal.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\BlogCategory', 'blog_category', 'blog_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public static function multiCategory($category_id, $language_id)
    {
        if ($category = BlogCategory::where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }
}
