<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\PageHelper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Models\NewsCategory;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Help;
use Spatie\SchemaOrg\Schema;
use App\Repositories\DownloadCategoryRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class NewsController extends Controller
{

    protected $news, $newsCategory, $preferredLanguage;

    public function __construct(NewsRepository $news, NewsCategory $newsCategory, DownloadCategoryRepository $downloadCategory)
    {
        $this->news = $news;
        $this->newsCategory = $newsCategory;
        $this->downloadCategory = $downloadCategory;
        $this->preferredLanguage = Helper::locale();
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function index()
    {
        $news = $this->news->with('category')->where('news.is_active', 1)
            ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
            ->where('published_date', '<=', Carbon::now())
            ->where('news.language_id', Helper::locale())
            // ->where(function ($query) {
            //     $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_CSR . '%')
            //         ->orWhere('type', 'like', '%' . ConstantHelper::NEWS_TYPE_ALL . '%');
            // })
            ->whereIn('news.category_id', [1, 2, 3, 4])
            ->select('news.*', 'news_categories.title as cat_title', 'news_categories.slug as cat_slug')
            ->orderBy('published_date', 'desc')->paginate('12');

        $menu = MenuItems::where('link_url', request()->path())->first();

        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(route('news.index'));

        return view('news.index')->withNews($news)->withMenu($menu);
    }

    public function show($category, $slug)
    {
        $news = $this->news->where('slug', $slug)->where('language_id', Helper::locale())
            // ->where(function ($query) {
            //     $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_NEWS . '%')
            //         ->orWhere('type', 'like', '%' . ConstantHelper::NEWS_TYPE_ALL . '%');
            // })
            ->where('is_active', 1)->first();
        if (!$news) {
            abort('404');
        }
        $category = $this->newsCategory->where('slug', $category)->first();
        if (!$category) {
            abort('404');
        }
        SEOMeta::setDescription(strip_tags(str_limit($news->description,200)));

        OpenGraph::setDescription(strip_tags(str_limit($news->description,200)));
        OpenGraph::setTitle($news->title);
        OpenGraph::addImages([isset($news->image) ? asset('storage/' . @$news->image) : asset('swabalamban/images/logo.svg')]);
        OpenGraph::setUrl(route('news.show',['category' => $category->slug, 'slug' => $news->slug]));


        $schema = Schema::newsArticle()
            ->wordCount(strlen($news->description))
            ->creator(Helper::schemaCreator())
            ->dateCreated($news->created_at)
            ->name($news->title)
            ->url(url('news/' . $news->slug))
            ->author(Helper::schemaAuthor())
            ->publisher(Schema::organization()->name(Helper::schemaPublisher())->logo(asset('kumari/images/logo.png'))->url(url()->full()))
            ->datePublished($news->published_date)
            ->headline($news->title)
            ->image($news->image);
        $schema = $schema->toScript();

        $latest = $this->news->with('category')->where('news.is_active', 1)
            ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
            ->where('published_date', '<=', Carbon::now())
            ->where('news.language_id', Helper::locale())
            ->whereNotIn('news.id', [$news->id])
            // ->where(function ($query) {
            //     $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_CSR . '%')
            //         ->orWhere('type', 'like', '%' . ConstantHelper::NEWS_TYPE_ALL . '%');
            // })
            ->select('news.*', 'news_categories.title as cat_title', 'news_categories.slug as cat_slug')
            ->orderBy('published_date', 'desc')->limit(3)->get();
        // switch ($news->layout) {
        //     case 3:
        //         $view = 'news.show-right';
        //         break;
        //     case 2:
        //         $view = 'news.show-left';
        //         break;
        //     default:
        //         $view = 'news.show';
        //         break;
        // }
        // dd($news);
        $download_categories = $this->downloadCategory->with('downloads')->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        return view('news.show', compact('news', 'latest', 'schema', 'category', 'download_categories'));
    }

    public function category(Request $request, $slug)
    {
        $category = $this->newsCategory->where('slug', $slug)->where('language_id', $this->preferredLanguage)->firstOrFail();
        $news = $this->news->where('is_active', 1)
            ->where('category_id', $category->id)
            ->where('published_date', '<=', Carbon::now())
            ->where('language_id', $this->preferredLanguage)
            ->orderBy('published_date', 'desc')->paginate('12');
        $ads = PageHelper::advertisements('news', 1);
        $menu = MenuItems::where('link_url', request()->path())->first();

        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(route('news.category',$category->slug));

        return view('news.category')->withNews($news)->withCategory($category)->withAds($ads);
    }
}
