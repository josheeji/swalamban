<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BonusCategory;
use App\Http\Requests\Admin\BonusCategoryRequest;
use App\Http\Requests\Admin\BonusRequest;
use App\Imports\BonusImport;
use App\Models\Bonus;
use App\Repositories\BonusCategoryRepository;
use App\Repositories\BonusRepository;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public $title = 'Bonus';
    protected $bonus, $category;

    public function __construct(
        BonusRepository $bonus,
        BonusCategoryRepository $category
    ) {
        $this->bonus = $bonus;
        $this->category = $category;

        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->bonus->orderBy('id', 'desc');
        if ($request->has('keyword')) {
            $keyword = strtoupper(str_replace(' ', '', $request->get('keyword')));
            $data = $data->orWhere('searchable_name', $keyword);
            $data = $data->orWhere('boid', $request->get('keyword'));
        }
        $data = $data->paginate(30);
        return view('admin.bonus.index', ['title' => $this->title, 'data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $this->authorize('master-policy.perform', ['bonus', 'edit']);
        $bonus = $this->bonus->find($id);
        $categories = $this->category->orderBy('title', 'asc')->get();

        return view('admin.bonus.edit', ['title' => $this->title, 'categories' => $categories, 'bonus' => $bonus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BonusRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['bonus', 'edit']);
        $data = $request->except(['_token']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        if ($bonus = $this->bonus->update($id, $data)) {
            return redirect()->route('admin.bonus.index')
                ->with('flash_notice', 'Data updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['bonus', 'delete']);
        $bonus = $this->bonus->find($id);
        if ($this->bonus->destroy($bonus->id)) {
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function import()
    {
        $categories = $this->category->where('is_active', 1)->get();
        return view('admin.bonus.import', ['title' => 'Bonus', 'categories' => $categories]);
    }

    public function saveImport(BonusCategoryRequest $request)
    {
        // $path = $request->file('file')->getRealPath();
        $path = $request->file('file');
        // Excel::import(new BonusImport, $path);
        // $title = $request->get('title');
        // if (!$category = $this->category->where('title', $title)->first()) {
        //     $category = $this->category->create(['title' => $title, 'is_active' => 1]);
        // }
        $rows = Excel::toArray(new BonusImport, $path);
        static $i = 0;
        $data = [];
        foreach ($rows as $k => $v) {
            foreach ($v as $key => $row) {
                if (is_numeric($row[0])) {
                    $data[$i]['category_id'] = $request->post('category_id');
                    $data[$i]['shareholder_no'] = isset($row[1]) ? $row[1] : '';
                    $data[$i]['boid'] = isset($row[2]) ? $row[2] : '';
                    $data[$i]['name'] = isset($row[3]) ? $row[3] : '';
                    $data[$i]['searchable_name'] = strtoupper(str_replace(' ', '', $data[$i]['name']));
                    $data[$i]['fathers_name'] = isset($row[4]) ? $row[4] : '';
                    $data[$i]['grandfathers_name'] = isset($row[5]) ? $row[5] : '';
                    $data[$i]['address'] = isset($row[6]) ? $row[6] : '';
                    $data[$i]['total'] = isset($row[7]) ? $row[7] : '';
                    $data[$i]['actual_bonus'] = isset($row[8]) ? $row[8] : '';
                    $data[$i]['created_by'] = auth()->user()->id;
                    $i++;
                }
            }
        }

        $chunk_data = array_chunk($data, 1000);
        if (isset($chunk_data) && !empty($chunk_data)) {
            foreach ($chunk_data as $chunk_data_val) {
                Bonus::insert($chunk_data_val);
            }
        }

        return redirect()->route('admin.bonus.import')
            ->with('flash_notice', 'Bonus imported successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['bonus', 'changeStatus']);
        $bonus = $this->bonus->find($request->get('id'));
        $status = $bonus->is_active == 0 ? 1 : 0;
        $message = $bonus->is_active == 0 ? 'Data is published.' : 'Data is unpublished.';

        $this->bonus->changeStatus($bonus->id, $status);
        $this->bonus->update($bonus->id, array('updated_by' => auth()->id()));
        $updated = $this->bonus->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function truncate(Request $request)
    {
        $this->authorize('master-policy.perform', ['bonus', 'edit']);
        DB::table('bonuses')->truncate();
        return redirect()->route('admin.bonus.index')
            ->with('flash_notice', 'Table truncated successfully.');
    }
}
