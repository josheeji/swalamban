<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Repositories\InternalWebCategoryRepository;
use App\Repositories\InternalWebRepository;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\NoticeRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;

class InternalWebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $internalWeb;
    protected $internalWebCategory;
    protected $preferredLanguage;

    public function __construct(InternalWebRepository $internalWeb, InternalWebCategoryRepository $internalWebCategory, NewsRepository $news, NoticeRepository $notice)
    {
        $this->internalWeb = $internalWeb;
        $this->internalWebCategory = $internalWebCategory;
        $this->news = $news;
        $this->notice = $notice;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
    }

    public function index()
    {
        $categories = $this->internalWebCategory->where('is_active', 1)->where('parent_id', null)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();

        $files = [];
        foreach ($categories as $category) {
            $files[] = $this->internalWeb->where('is_active', 1)
                ->where('language_id', Helper::locale())
                ->orderBy('year', 'desc')
                ->where('category_id', $category->id)
                // ->where('year', '<>', null)
                ->get()
                // ->groupBy(['year', 'month']);
                ->groupBy(['year']);
        }
        // dd($files);

        // $downloads = $this->internalWeb->where('is_active', 1)
        //     ->where('language_id', Helper::locale())
        //     ->orderBy('year', 'desc')
        //     ->where('category_id', $categories->first()->id)
        //     ->get()
        //     ->groupBy(['year', 'month']);
        $menu = MenuItems::where('link_url', request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Internal Web.');

        OpenGraph::setDescription($menu->title ??   'Internal Web.');
        OpenGraph::setTitle($menu->title ?? 'Internal Web');
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('internalWeb.index', ['categories' => $categories, 'downloads' => $files, 'menu' => $menu]);
    }

    public function show($year, $slug)
    {
        $category = $this->internalWebCategory->where('language_id', Helper::locale())->where('slug', $slug)->where('is_active', 1)->firstOrFail();
        if (empty($category)) {
            abort('404', 'Page not found.');
        }
        $files = $this->internalWeb->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('year', 'desc')
            ->where('category_id', $category->id)
            ->where('year', $year)
            ->get();
        // ->groupBy(['month']);
        // dd($files);
        $categories = $this->internalWebCategory->where('is_active', 1)->where('parent_id', null)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();

        // dd($categories->first()->id);
        foreach ($categories as $cat) {
            $downloads[] = $this->internalWeb->where('is_active', 1)
                ->where('language_id', Helper::locale())
                ->orderBy('year', 'desc')
                ->where('category_id', $cat->id)
                ->where('year', '<>', null)
                ->get()
                ->groupBy(['year', 'month']);
        }
        $menu = MenuItems::where('link_url', request()->path())->first();
        $menu = MenuItems::where('link_url', request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Internal Web.');

        OpenGraph::setDescription($menu->title ??   'Internal Web.');
        OpenGraph::setTitle($menu->title ?? 'Internal Web');
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('internalWeb.show', ['year' => $year, 'category' => $category, 'files' => $files, 'categories' => $categories, 'downloads' => $downloads, 'menu' => $menu]);
    }

    public function category($slug)
    {
        $category = $this->internalWebCategory->where('language_id', Helper::locale())->where('slug', $slug)->where('is_active', 1)->firstOrFail();
        // dd($category->allChild->pluck('id'));
        if (empty($category)) {
            abort('404', 'Page not found.');
        }
        $categoryIds = $category->allChild->pluck('id')->toArray();
        array_push($categoryIds, $category->id);
        // dd($categoryIds);
        $files = $this->internalWeb->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('year', 'desc')
            ->whereIn('category_id', $categoryIds)
            ->get()
            ->groupBy(['year']);
        // dd($files);
        $categories = $this->internalWebCategory->where('is_active', 1)->where('parent_id', null)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();

        // dd($categories->first()->id);
        foreach ($categories as $cat) {
            $downloads[] = $this->internalWeb->where('is_active', 1)
                ->where('language_id', Helper::locale())
                ->orderBy('year', 'desc')
                ->where('category_id', $cat->id)
                // ->where('year', '<>', null)
                ->get()
                ->groupBy(['year', 'month']);
        }
        $menu = MenuItems::where('link_url', request()->path())->first();
        $menu = MenuItems::where('link_url', request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Internal Web.');

        OpenGraph::setDescription($category->title ??   'Internal Web.');
        OpenGraph::setTitle($category->title ?? 'Internal Web');
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('internalWeb.category', ['category' => $category, 'files' => $files, 'categories' => $categories, 'downloads' => $downloads, 'menu' => $menu]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'year' => 'nullable',
            'month' => 'nullable',
            'file' => 'required|max:30480|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx,zip',
        ]);
        $data = $request->except(['file']);
        if ($request->hasFile('file')) {
            // $filelocation = MediaHelper::upload($request->file('file'), 'download');
            // $data['file'] = $filelocation['storage'];
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'internal-web',  $fileName);
            $data['file'] = $filelocation;
        }
        $preferred_language_item = $data;
        $preferred_language = $this->preferredLanguage;
        $preferred_language_item['language_id'] = $preferred_language;
        try {
            $preferred_insert = $this->internalWeb->create($preferred_language_item);
            $lang_items = [
                'title' => $preferred_insert->title,
                'slug' => $preferred_insert->slug,
                'file' => $preferred_insert->file,
                'category_id' => $preferred_insert->category_id,
                'year' => $preferred_insert->year ?? null,
                'month' => $preferred_insert->month ?? null,
                'language_id' => 2
            ];
            if (!empty($lang_items)) {
                $this->internalWeb->model()->insert($lang_items);
            }
            return redirect()->back()->with('flash_success', 'File uploaded successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput();
        }
    }
}
