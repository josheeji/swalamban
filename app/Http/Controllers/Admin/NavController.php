<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NavCategoryRequest;
use App\Http\Requests\Admin\NavRequest;
use App\Imports\NavImport;
use App\Models\Nav;
use App\Repositories\NavCategoryRepository;
use App\Repositories\NavRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;
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
    public function index()
    {
        $this->authorize('master-policy.perform', ['nav', 'view']);
        $data = $this->nav->orderBy('display_order', 'asc')->get();
        return view('admin.nav.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['nav', 'add']);
        $categories = $this->category->orderBy('display_order', 'asc')->get();
        return view('admin.nav.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NavRequest $request)
    {
        $this->authorize('master-policy.perform', ['nav', 'add']);
        $data = $request->except(['_token']);
        if ($model = $this->nav->create($data)) {
            return redirect()->route('admin.navs.edit', $model->id)->with('flash_success', 'NAV created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'NAV can not be created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['nav', 'edit']);
        $data = $this->nav->find($id);
        $categories = $this->category->orderBy('display_order', 'asc')->get();
        return view('admin.nav.edit', ['data' => $data, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NavRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['nav', 'edit']);
        $data = $request->except(['_token']);
        if ($this->nav->update($id, $data)) {
            return redirect()->route('admin.navs.index')->with('flash_success', 'NAV updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'NAV can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['nav-category', 'delete']);
        if ($this->nav->find($id)->delete()) {
            $message = 'NAV deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['nav-category', 'changeStatus']);
        $data = $this->nav->find($request->get('id'));
        $status = $data->is_active == 0 ? 1 : 0;
        $message = $data->is_active == 0 ? 'Category is published.' : 'Category is unpublished.';
        $this->nav->changeStatus($data->id, $status);
        $this->nav->update($data->id, array('updated_by' => auth()->id()));
        $updated = $this->nav->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->nav->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully re-ordered.'], 200);
    }

    public function import()
    {
        $this->authorize('master-policy.perform', ['nav', 'add']);
        return view('admin.nav.import');
    }

    public function storeImport(NavCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['nav', 'add']);
        $path = $request->file('file');
        $title = $request->get('title');
        if (!$category = $this->category->where('title', $title)->first()) {
            $category = $this->category->create(['title' => $title, 'is_active' => 1]);
        }
        $rows = Excel::toArray(new NavImport, $path);
        static $i = 0;
        $data = [];
        foreach ($rows as $k => $v) {
            foreach ($v as $key => $row) {
                if (is_numeric($row[0])) {
                    $data[$i]['category_id'] = $category->id;
                    $data[$i]['publish_at'] = isset($row[0]) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0])) : '';
                    $data[$i]['value'] = isset($row[1]) ? $row[1] : '';
                    $data[$i]['created_by'] = auth()->user()->id;
                    $i++;
                }
            }
        }
        $chunk_data = array_chunk($data, 1000);
        if (isset($chunk_data) && !empty($chunk_data)) {
            foreach ($chunk_data as $chunk_data_val) {
                Nav::insert($chunk_data_val);
            }
        }
        return redirect()->route('admin.navs.index')->with('flash_success', 'NAVs imported successfully.');
    }

    public function truncate(Request $request)
    {
        $this->authorize('master-policy.perform', ['nav', 'delete']);
        DB::table('nav')->truncate();

        return redirect()->route('admin.navs.index')->with('flash_success', 'Table truncated successfully.');
    }
}
