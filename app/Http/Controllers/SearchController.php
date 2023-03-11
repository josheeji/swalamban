<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\AgmReport;
use App\Models\AtmLocation;
use App\Models\Blog;
use App\Models\BranchDirectory;
use App\Models\Content;
use App\Models\Download;
use App\Models\Faq;
use App\Models\FinancialReport;
use App\Models\News;
use App\Models\Notice;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Team;
use App\Repositories\SearchRepository;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    protected $search;

    public function __construct(SearchRepository $search)
    {
        $this->search = $search;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $word = $this->RemoveSpecialChapr($keyword);
        $searchResults = [];
        if (!empty($keyword)) {
            $data['keyword'] = $word;
            $data['ip_address'] = $request->ip();
            $data['agent'] = $request->header('User-Agent');
            $this->search->create($data);
            $searchResults = (new Search())
                ->registerModel(Content::class, ['title', 'excerpt', 'description'])
                ->registerModel(AccountType::class, ['title', 'excerpt', 'description'])
                ->registerModel(News::class, ['title', 'excerpt', 'description'])
                ->registerModel(Download::class, ['title'])
                // ->registerModel(Faq::class, ['question', 'answer'])
                // ->registerModel(Team::class, ['full_name'])
                ->registerModel(Notice::class, ['title', 'excerpt', 'description'])
                // ->registerModel(Blog::class, ['title', 'excerpt', 'description'])
                ->limitAspectResults(7)
                ->search($word);
        }
        // dd($searchResults);
        return view('search.index', compact('searchResults', 'keyword'));
    }

    public function RemoveSpecialChapr($value)
    {
        $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $value);

        return $title;
    }
}
