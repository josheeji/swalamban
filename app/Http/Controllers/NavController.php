<?php

namespace App\Http\Controllers;

use App\Repositories\NavCategoryRepository;
use App\Repositories\NavRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavController extends Controller
{
    protected $nav;
    protected $category;

    public function __construct(NavRepository $nav, NavCategoryRepository $category)
    {
        $this->nav = $nav;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->category->where('is_active', 1)->orderBy('display_order', 'asc')->get();
        // DB::enableQueryLog();
        $navs = $this->nav->where('is_active', 1);
        if ($request->has('category')) {
            $navs = $navs->where('category_id', $request->get('category'));
        } else {
            if (!empty($categories) && isset($categories[0])) {
                $navs = $navs->where('category_id', $categories[0]->id);
            }
        }
        if ($request->has('year') && !empty($request->get('year'))) {
            $navs = $navs->whereYear('publish_at', '=', $request->get('year'));
        }
        if ($request->has('type')) {
            switch ($request->get('type')) {
                case 1:
                    $navs =  $navs->groupBy(DB::raw('WEEK(publish_at)'));
                    break;
                case 2:
                    $navs = $navs->groupBy(DB::raw('MONTH(publish_at)'));
                    break;
            }
        } else {
            $navs->groupBy(DB::raw('MONTH(publish_at)'));
        }
        $navs =  $navs->pluck('value', 'publish_at');
        // $query = DB::getQueryLog();
        // $query = end($query);
        // print_r($query);
        // dd($navs);

        $data = [];
        if ($navs) {
            foreach ($navs as $key => $value) {
                $data[] = ['y' => $value, 'label' => $key];
            }
        }
        $data = json_encode($data);

        return view('nav.index', ['categories' => $categories, 'data' => $data])->withInputs($request);
    }

    public function table(Request $request)
    {
        // DB::enableQueryLog();
        $data = $this->nav->where('category_id', $request->post('category'));
        if ($request->has('type')) {
            switch ($request->post('type')) {
                case 1:
                    $data =  $data->groupBy(DB::raw('WEEK(publish_at)'));
                    break;
                case 2:
                    $data =  $data->groupBy(DB::raw('MONTH(publish_at)'));
                    break;
            }
        }
        $data = $data->get();
        // $query = DB::getQueryLog();
        // print_r($query);
        // dd($data);

        return view('nav.table', ['data' => $data]);
    }
}
