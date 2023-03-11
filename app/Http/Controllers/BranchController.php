<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\BranchDirectory;
use App\Models\MenuItems;
use App\Repositories\BranchDirectoryRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class BranchController extends Controller
{

    protected $atm;
    protected $province;
    protected $district;
    protected $locale_id;

    public function __construct(BranchDirectoryRepository $branch, ProvinceRepository $province, DistrictRepository $district)
    {
        $this->branch = $branch;
        $this->province = $province;
        $this->district = $district;
        $this->locale_id = session()->get('locale_id');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = $this->branch->model()->where('type',1)->where('is_active', 1)->where('language_id', Helper::locale());
        if ($request->has('province_id') && !empty($request->province_id) && $request->province_id != 'all') {
            $branches = $branches->where('province_id', $request->province_id);
        }
        if ($request->has('title') && !empty($request->title)) {
            $branches = $branches->where('title', 'like', '%' . $request->title . '%');
        }
        $branches = $branches->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        $menu = MenuItems::where('link_url',request()->path())
        ->orWhere('link_url','/'.request()->path())->first();
        return view('branch.index', ['branches' => $branches, 'provinces' => $provinces,'menu'=>$menu]);
    }
    public function centralOffice(Request $request)
    {
        $branches = $this->branch->model()->where('type',2)->where('is_active', 1)->where('language_id', Helper::locale());
        if ($request->has('province_id') && !empty($request->province_id) && $request->province_id != 'all') {
            $branches = $branches->where('province_id', $request->province_id);
        }
        if ($request->has('title') && !empty($request->title)) {
            $branches = $branches->where('title', 'like', '%' . $request->title . '%');
        }
        $branches = $branches->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        $menu = MenuItems::where('link_url',request()->path())->orWhere('link_url','/'.request()->path())->first();

        return view('branch.index', ['branches' => $branches, 'provinces' => $provinces,'menu'=>$menu]);
    }
    public function areaOffice(Request $request)
    {
        $branches = $this->branch->model()->where('type',3)->where('is_active', 1)->where('language_id', Helper::locale());
        if ($request->has('province_id') && !empty($request->province_id) && $request->province_id != 'all') {
            $branches = $branches->where('province_id', $request->province_id);
        }
        if ($request->has('title') && !empty($request->title)) {
            $branches = $branches->where('title', 'like', '%' . $request->title . '%');
        }
        $branches = $branches->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        $menu = MenuItems::where('link_url',request()->path())->orWhere('link_url','/'.request()->path())->first();

        return view('branch.index', ['branches' => $branches, 'provinces' => $provinces,'menu'=>$menu]);
    }
    public function informationOffice(Request $request)
    {
        $branches = $this->branch->model()->where('type',4)->where('is_active', 1)->where('language_id', Helper::locale());
        if ($request->has('province_id') && !empty($request->province_id) && $request->province_id != 'all') {
            $branches = $branches->where('province_id', $request->province_id);
        }
        if ($request->has('title') && !empty($request->title)) {
            $branches = $branches->where('title', 'like', '%' . $request->title . '%');
        }
        $branches = $branches->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        $menu = MenuItems::where('link_url',request()->path())->orWhere('link_url','/'.request()->path())->first();

        return view('branch.index', ['branches' => $branches, 'provinces' => $provinces,'menu'=>$menu]);
    }

    public function result(Request $request)
    {
        $branches = $this->branch->model()->with('district', 'province')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        if ($request->province_id == 999) {
            $result = $this->branch->where('language_id', Helper::locale())->get();
        } else {
            $result = $this->branch->where('province_id', $request->province_id)->get();
        }
        return view('branch.index')->with(['result' => $result, 'branches' => $branches, 'provinces' => $provinces, 'post' => true]);
    }

    public function search(Request $request)
    {
        $search = $request->input('title');
        $branches = $this->branch->model()->with('district', 'province')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('title', 'asc')->get();
        $provinces = $this->province->where('language_id', Helper::locale())->get();
        $search = BranchDirectory::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%")
            ->get();
        return view('branch.index')->with(['result' => $search, 'branches' => $branches, 'provinces' => $provinces, 'longitude' => $longitude, 'latitude' => $latitude, 'post' => true]);
    }

    public function show($slug)
    {
        $branch = $this->branch->where('slug', $slug)->where('language_id', Helper::locale())->where('is_active', 1)->first();
        if (!$branch) {
            abort('404');
        }
        $schema = Schema::organization()
            ->name($branch->title)
            ->url(url('branch/' . $branch->slug))
            ->logo(asset('kumari/images/logo.png'))
            ->image(asset('kumari/images/logo.png'))
            ->address($branch->address)
            ->email($branch->email)
            ->telephone($branch->telephone);
        $schema = $schema->toScript();
        return view('branch.show')->withBranch($branch)
            ->withSchema($schema);
    }
}
