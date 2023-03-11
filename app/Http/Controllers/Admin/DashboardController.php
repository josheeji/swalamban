<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Analytics;
use App\Models\Search;
use App\Repositories\SearchRepository;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        AdminTypeRepository $adminType,
        SearchRepository $search
    ) {
        $this->middleware('auth:admin');
        auth()->shouldUse('admin');
        $this->adminType = $adminType;
        $this->search = $search;
    }

    public function index(Request $request)
    {
        $searchReport = $this->search($request);
        // $admin = auth()->user();
        // if (request()->start_date && request()->end_date) {
        //     $from_date = request()->start_date;
        //     $to_date = request()->end_date;
        //     $period = Period::create(Carbon::createFromFormat('Y-m-d', request()->start_date), Carbon::createFromFormat('Y-m-d', request()->end_date));
        // } else {
        //     $from_date = Carbon::now()->subDays(7);
        //     $to_date = date('Y-m-d');
        //     $period = Period::days(7);
        // }

        // $analyticsData = Analytics::fetchTotalVisitorsAndPageViews($period);

        // $dates = [];
        // $visitors = [];
        // $page_views = [];
        // foreach ($analyticsData as $data) {
        //     $dates[] = $data['date']->toDateString();
        //     $visitors[] = $data['visitors'];
        //     $page_views[] = $data['pageViews'];
        // }

        // $dates_json = json_encode($dates);
        // $visitors_json = json_encode($visitors);
        // $page_views_json = json_encode($page_views);

        // $visitedPages = Analytics::fetchMostVisitedPages($period, 5);
        // $topBrowsers = Analytics::fetchTopBrowsers($period, 5);

        // $browser_values = [];
        // $browsers = [];
        // foreach ($topBrowsers as $browser) {
        //     $browsers[] = $browser['browser'];
        //     $browser_values[] = $browser['sessions'];
        // }
        // $browser_value_json = json_encode($browser_values);
        // $browser_json = json_encode($browsers);

        return view('admin.dashboard', [
            'searchReport' => $searchReport,
            // 'dates_json' => $dates_json,
            // 'visitors_json' => $visitors_json,
            // 'page_views_json' => $page_views_json,
            // 'visitedPages' => $visitedPages,
            // 'browser_json' => $browser_json,
            // 'browser_value_json' => $browser_value_json
        ]);
    }

    protected function searchReport($request)
    {
        $topKeywords = DB::table('searches')
            ->select('keyword', DB::raw('count(*) as total'), 'created_at')
            ->groupBy('keyword')
            // ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy("created_at", 'asc')
            ->get();

        return $topKeywords;
    }

    public function analytics(Request $request)
    {
        $admin = auth()->user();
        if (request()->start_date && request()->end_date) {
            $from_date = request()->start_date;
            $to_date = request()->end_date;
            $period = Period::create(Carbon::createFromFormat('Y-m-d', request()->start_date), Carbon::createFromFormat('Y-m-d', request()->end_date));
        } else {
            $from_date = Carbon::now()->subDays(7);
            $to_date = date('Y-m-d');
            $period = Period::days(7);
        }

        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews($period);
        dd($analyticsData);

        $dates = [];
        $visitors = [];
        $page_views = [];
        foreach ($analyticsData as $data) {
            $dates[] = $data['date']->toDateString();
            $visitors[] = $data['visitors'];
            $page_views[] = $data['pageViews'];
        }

        $dates_json = json_encode($dates);
        $visitors_json = json_encode($visitors);
        $page_views_json = json_encode($page_views);

        $visitedPages = Analytics::fetchMostVisitedPages($period, 5);
        $topBrowsers = Analytics::fetchTopBrowsers($period, 5);

        $browser_values = [];
        $browsers = [];
        foreach ($topBrowsers as $browser) {
            $browsers[] = $browser['browser'];
            // $browser_values[] = [
            //     'value' => $browser['sessions'],
            //     'name' => $browser['browser']
            // ];
            $browser_values[] = $browser['sessions'];
        }
        $browser_value_json = json_encode($browser_values);
        $browser_json = json_encode($browsers);

        // dd($browser_json);
        return view('admin.analytics', compact('dates_json', 'visitors_json', 'page_views_json', 'browser_json', 'browser_value_json', 'visitedPages'));
    }

    protected function search(Request $request)
    {
        if (request()->start_date && request()->end_date) {
            $from_date = request()->start_date;
            $to_date = request()->end_date;
        } else {
            $from_date = Carbon::now()->subDays(7);
            $to_date = date('Y-m-d');
        }

        $count = DB::raw('count(*) as total');
        $report = Search::getQuery()
            ->select('keyword', $count)
            ->whereBetween('created_at', [$from_date . " 00:00:00", $to_date . " 23:59:59"])
            ->groupBy('keyword')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return $report;
    }
}
