<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Repositories\NewsCategoryRepository;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CsrController extends Controller
{
    public function __construct(NewsRepository $news, NewsCategoryRepository $newsCategory)
    {
        $this->news = $news;
        $this->newsCategory = $newsCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = $this->news->with('category')->where('news.is_active', 1)
        ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
        ->where('published_date', '<=', Carbon::now())
        ->where('news.language_id', Helper::locale())
        ->whereIn('news.category_id', [5,6])
        ->select('news.*', 'news_categories.title as cat_title', 'news_categories.slug as cat_slug')
        ->orderBy('published_date', 'desc')->paginate('12');

        // dd($news);
    return view('news.index')->withNews($news);
        // return view('csr.index', ['news' => $news]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $news = $this->news->where('slug', $slug)->where('language_id', Helper::locale())->where('is_active', 1)->firstOrFail();
        switch ($news->layout) {
            case 3:
                $view = 'csr.show-right';
                break;
            case 2:
                $view = 'csr.show-left';
                break;
            default:
                $view = 'csr.show';
                break;
        }
        return view($view, ['news' => $news]);
    }
}
