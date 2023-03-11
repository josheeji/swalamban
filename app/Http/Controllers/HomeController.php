<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\LayoutHelper;
use App\Helper\PageHelper;
use App\Helper\SettingHelper;
use App\Models\BodyMenu;
use App\Repositories\AccountTypeRepository;
use App\Repositories\BannerRepository;
use App\Repositories\BlogRepository;
use App\Repositories\BodyMenuRepository;
use App\Repositories\ContentRepository;
use App\Repositories\ForexRepository;
use App\Repositories\LoanGraphRepository;
use App\Repositories\NewsRepository;
use App\Repositories\PostRepository;
use App\Repositories\StatisticsRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Validator;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $preferredLanguage, $banner, $content, $post, $accountType, $news, $forex, $loanGraph, $statistics, $bodyMenu;

    public function __construct(
        BannerRepository      $banner,
        ContentRepository $content,
        PostRepository $post,
        AccountTypeRepository $accountType,
        NewsRepository $news,
        ForexRepository $forex,
        BlogRepository        $blog,
        LoanGraphRepository $loanGraph,
        StatisticsRepository $statistics,
        BodyMenuRepository $bodyMenu
    ) {
        $this->banner = $banner;
        $this->content = $content;
        $this->post = $post;
        $this->accountType = $accountType;
        $this->news = $news;
        $this->preferredLanguage = session()->get('locale_id');
        $this->forex = $forex;
        $this->blog = $blog;
        $this->loanGraph = $loanGraph;
        $this->statistics = $statistics;
        $this->bodyMenu = $bodyMenu;
    }

    public function index(Request $request)
    {
        $banners = PageHelper::banner();
        $blocks = LayoutHelper::blocks();
        $products = PageHelper::product();
        $news = PageHelper::news();
        $csr = PageHelper::csr();
        $notices = PageHelper::pressReleases();
        $galleries = PageHelper::galleries(9);
        $partners = PageHelper::partners();
        
        SEOMeta::setDescription(!empty(SettingHelper::multiLangSetting('tagline')) ?SettingHelper::multiLangSetting('tagline') :  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription(!empty(SettingHelper::multiLangSetting('tagline')) ?SettingHelper::multiLangSetting('tagline') :  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle(SettingHelper::setting('site_title'));
        OpenGraph::addImages([asset('swabalamban/images/logo.svg')]);
        OpenGraph::setUrl(route('home.index'));

        return view('home.index', [
            'banners' => $banners,
            'blocks' => $blocks,
            'products' => $products,
            'news' => $news,
            'notices' => $notices,
            'galleries' => $galleries,
            'csr' => $csr,
            'partners' => $partners,
        ]);
    }

    public function postemail(Request $request)
    {
        $rules = [
            'email' => 'required|unique:news_subscribe,email'
        ];
        $message = [
            'email.required' => 'please insert your email address'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['status' => 'false', 'errors' => $validator->errors()]);
        }
        $data = $request->all();
        $data['is_active'] = '1';

        if ($this->newsSubscribe->create($data)) {
            $message = 'Subscription Successful';
            return response()->json(['status' => 'true', 'message' => $message]);

            return redirect()->route('home')->with('success', 'Email is Save');
        }
        $message = 'Email is not save';
        return response()->json(['status' => 'false', 'errors' => $message]);
    }

    public function projectShow($slug)
    {
        dd($slug);
    }
}
