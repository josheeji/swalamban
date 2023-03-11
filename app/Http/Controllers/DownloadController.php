<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Repositories\DownloadCategoryRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\NoticeRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $download;
    protected $downloadCategory;

    public function __construct(DownloadRepository $download, DownloadCategoryRepository $downloadCategory, NewsRepository $news, NoticeRepository $notice)
    {
        $this->download = $download;
        $this->downloadCategory = $downloadCategory;
        $this->news = $news;
        $this->notice = $notice;
    }

    public function index()
    {
        $categories = $this->downloadCategory->with('downloads')->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();

        $downloads = $this->download->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $notices = $this->notice->where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();
        $menu = MenuItems::where('link_url', request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('download.index', ['categories' => $categories, 'downloads' => $downloads, 'notices' => $notices, 'menu' => $menu]);
    }

    public function show($slug)
    {
        $category = $this->downloadCategory->where('language_id', Helper::locale())->where('slug', $slug)->where('is_active', 1)->firstOrFail();
        if (empty($category)) {
            abort('404', 'Page not found.');
        }
        if (!empty($category)) {
            $child = $this->downloadCategory->with('downloads')->where('language_id', Helper::locale())
                ->orderBy('display_order', 'asc')
                ->where('parent_id', $category->id)->where('is_active', 1)->get();
        } else {
            $child = [];
        }
        $downloads = $this->download->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->where('category_id', $category->id)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = $this->downloadCategory->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        $notices = $this->notice->where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();
        $menu = MenuItems::where('link_url', request()->path())->first();
        return view('download.show', ['category' => $category, 'downloads' => $downloads, 'child' => $child, 'categories' => $categories,'notices' => $notices,'menu' => $menu]);
    }

    public function career(Request $request)
    {
        $downloads = '';
        if ($category = $this->category('career')) {
            $downloads = $this->download->where('is_active', 1)->where('language_id', Helper::locale())->where('category_id', $category->id)->orderBy('published_date', 'desc')->paginate(12);
        }
        return view('download.career', ['downloads' => $downloads]);
    }

    protected function category($slug)
    {
        return $this->downloadCategory->where('is_active', 1)->where('language_id', Helper::locale())->where('slug', $slug)->first();
    }
}
