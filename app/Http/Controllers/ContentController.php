<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\PageHelper;
use App\Helper\SettingHelper;
use App\Models\DownloadCategory;
use App\Models\Notice;
use App\Repositories\ContentBlockRepository;
use App\Repositories\ContentRepository;
use App\Repositories\ForexRepository;
use App\Repositories\LoanGraphRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\NewsRepository;
use App\Repositories\StatisticsRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Spatie\SchemaOrg\Schema;

class ContentController extends Controller
{

    protected $content, $news, $preferredLanguage, $forex, $menuItem;

    public function __construct(
        ContentRepository $content,
        NewsRepository $news,
        ForexRepository $forex,
        MenuItemRepository $menuItem,
        ContentBlockRepository $contentBlock,
        StatisticsRepository $statistics,
        LoanGraphRepository $loanGraph
    ) {
        $this->content = $content;
        $this->news = $news;
        $this->preferredLanguage = session()->get('locale_id');
        $this->forex = $forex;
        $this->menuItem = $menuItem;
        $this->contentBlock = $contentBlock;
        $this->statistics = $statistics;
        $this->loanGraph = $loanGraph;
    }

    public function show($page, $slug = null)
    {
        // $loanGraphArr = [];
        // $loanGraph = $this->loanGraph->where('is_active', 1)->where('language_id', Helper::locale())->pluck('value', 'title')->toArray();
        // if (count($loanGraph)) {
        //     foreach ($loanGraph as $key => $value) {
        //         $loanGraphArr[] = ['value' => $value, 'name' => $key];
        //     }
        // }
        // $loanGraphKey = array_keys($loanGraph);

        $slug = empty($slug) ? $page : $slug;
        $this->preferredLanguage = Helper::locale();
        $content = $this->content->where('slug', $slug)->where('language_id', $this->preferredLanguage)->where('is_active', 1)->first();
        if (!$content) {
            abort('404');
        }
        // $blocks = $this->contentBlock->where('content_id', $content->id)->where('language_id', $this->preferredLanguage)->where('is_active', 1)->get();
        // $lineGraphYear = $this->statistics->where('is_active', 1)->where('language_id', $this->preferredLanguage)->pluck('year')->toArray();
        // $lineGraphNetProfit = $this->statistics->where('is_active', 1)->where('language_id', Helper::locale())->pluck('earning')->toArray();
        // $lineGraphOutstanding = $this->statistics->where('is_active', 1)->where('language_id', Helper::locale())->pluck('expenses')->toArray();
        $schema = Schema::article()->articleBody($content->description)
            ->wordCount(strlen($content->description))
            ->about($content->title)
            ->headline($content->title)
            ->author(Helper::schemaAuthor())
            ->creator(Helper::schemaCreator())
            ->dateCreated($content->created_at)
            ->dateModified($content->updated_at)
            ->datePublished($content->created_at)
            // ->editor(Helper::schemaEditor())
            ->keywords(@$content->meta_keys)
            ->text($content->description)
            ->description($content->description)
            ->name($content->title)
            ->image(asset('frontend/images/logo.png'))
            ->publisher(Schema::organization()->name(Helper::schemaPublisher())->logo(asset('frontend/images/logo.png'))->url(url()->full()))
            ->url(url()->full());
        $schema = $schema->toScript();

        // $landingPage =  session()->has('site_settings.landing_pages') ? session('site_settings')['landing_pages'] : '';
        // if (in_array($content->slug, explode(', ', $landingPage))) {
        //     $banners = PageHelper::banner($content->slug);
        //     $offers = PageHelper::offer($content->slug);
        //     $products = PageHelper::product($content->slug);
        //     $services = PageHelper::service($content->slug);
        //     $news = PageHelper::news($content->slug);
        //     $popups = PageHelper::popup($content->slug);
        //     $ads = PageHelper::advertisements($content->slug, 3);
        //     $title = $content->title;

        //     $bannerItems = PageHelper::bannerMenu();

        //     $forexes = $this->forex->whereIn('FXD_CRNCY_CODE', ['USD', 'AUD', 'INR', 'GBP', 'EUR', 'CHF', 'CAD', 'SGD', 'JPY', 'HKD'])->orderByRaw('FIELD(FXD_CRNCY_CODE, "INR", "USD", "EUR", "GBP","CHF", "AUD", "CAD", "SGD", "JPY", "HKD")')->get();

        //     return view('frontend.home', compact('title', 'banners', 'bannerItems', 'offers', 'products', 'services', 'news', 'popups', 'forexes', 'ads'));
        // }
        // $menu = $this->menuItem->with('parent')->where('reference_id', $content->id)->where('language_id', $this->preferredLanguage)->where('is_active', 1)->first();
        $categories = DownloadCategory::whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        $notices = Notice::where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();
        switch ($content->layout) {
            case 3:
                $view = 'content.show-right';
                break;
            case 2:
                $view = 'content.show-left';
                break;
            case 4:
                $view = 'content.iframe';
                break;
            default:
                $view = 'content.show';
                break;
        }
        SEOMeta::setDescription(strip_tags(str_limit($content->title,200)) ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription(strip_tags(str_limit($content->title,200)) ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($content->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($content->image) ? asset('storage/' . @$content->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        // $view = 'content.show';
        return view($view)
            ->withNotices($notices)
            ->withCategories($categories)
            ->withContent($content)
            // ->withMenu($menu)
            ->withSchema($schema);
    }
}
