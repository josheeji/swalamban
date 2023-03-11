<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Repositories\BlogBlockRepository;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class BlogController extends Controller
{
    protected $blog, $blogCategory;
    public function __construct(BlogRepository $blog, BlogCategoryRepository $blogCategory, BlogBlockRepository $blogBlock)
    {
        $data['showSearch'] = true;
        $this->blog = $blog;
        $this->blogCategory = $blogCategory;
        $this->blogBlock = $blogBlock;
    }

    public function index()
    {
        $data['showSearch'] = true;
        $data['blogs'] = $this->blog->where('language_id', Helper::locale())
            ->where('is_active', 1)
            ->orderBy('published_date', 'desc')
            ->paginate(12);
        $data['menu'] = MenuItems::where('link_url',request()->path())->first();
        SEOMeta::setDescription($data['menu']->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($data['menu']->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($data['menu']->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($data['menu']->image) ? asset('storage/' . @$data['menu']->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('blog.list', $data);
    }

    public function category($category)
    {
        $category = $this->blogCategory->where('slug', $category)->where('language_id', Helper::locale())->where('is_active', 1)->firstOrFail();
        $data['category'] = $category;
        $data['showSearch'] = true;
        $data['blog'] = $this->blog->where('language_id', Helper::locale())->orderBy('created_at', 'desc')->where('is_active', '1')->where('category_id', $category->id)->paginate(10);
        return view('blog.list', $data);
    }

    public function detail($slug)
    {
        $data['showSearch'] = true;
        $data['blog'] = $this->blog->where('slug', $slug)->where('language_id', Helper::locale())->where('is_active', 1)->first();
        if (!$data['blog'])
            return view('errors.404', $data);

        $data['latest_blog'] = $this->blog->model()->where('language_id', Helper::locale())->where('is_active', '1')->where('id', '!=', $data['blog']->id)->orderBy('id', 'desc')->take(5)->get();
        $data['blocks'] = $this->blogBlock->where('blog_id', $data['blog']->id)->where('language_id', Helper::locale())->where('is_active', 1)->get();

        $schema = Schema::article()->articleBody($data['blog']->description)
            ->wordCount(strlen($data['blog']->description))
            ->about($data['blog']->title)
            ->headline($data['blog']->title)
            ->author('Yeti Airlines')
            ->creator('Yeti Airlines')
            ->dateCreated($data['blog']->created_at)
            ->dateModified($data['blog']->updated_at)
            ->datePublished($data['blog']->created_at)
            ->editor('Yeti Airlines')
            ->keywords($data['blog']->meta_keys)
            ->publisher(Schema::organization()->name('Yeti Airlines'))
            ->text($data['blog']->description)
            ->description($data['blog']->description)
            ->name($data['blog']->title)
            ->image(asset('images/logo.png'))
            ->url(url()->full());

        $data['schema'] = $schema->toScript();
        // $data['categories'] = $this->blogCategory->where('language_id', Helper::locale())->where('is_active', 1)->orderBy('display_order', 'asc')->get();
        $data['menu'] = MenuItems::where('link_url','stories')->first();

        SEOMeta::setDescription(strip_tags(str_limit($data['blog']->description,200)) ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription(strip_tags(str_limit($data['blog']->description,200)) ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($data['blog']->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($data['blog']->image) ? asset('storage/' . @$data['blog']->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('blog.detail', $data);
    }
}
