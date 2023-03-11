<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NavCategoryRequest;
use App\Repositories\NavCategoryRepository;
use Illuminate\Http\Request;

class NavCategoryController extends Controller
{
    protected $category;

    public function __construct(NavCategoryRepository $category)
    {
        $this->category = $category;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['nav-category', 'view']);
        $data = $this->category->orderBy('title', 'asc')->get();
        return view('admin.navCategory.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['nav-category', 'add']);
        return view('admin.navCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NavCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['nav-category', 'add']);
        $data = $request->except(['_token']);
        if ($model = $this->category->create($data)) {
            return redirect()->route('admin.nav-categories.edit', $model->id)->with('flash_success', 'Category created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Category can not be created.');
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
        $this->authorize('master-policy.perform', ['nav-category', 'edit']);
        $data = $this->category->find($id);
        return view('admin.navCategory.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NavCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['nav-category', 'edit']);
        $data = $request->except(['_token']);
        if ($this->category->update($id, $data)) {
            return redirect()->route('admin.nav-categories.index')->with('flash_success', 'Category updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Category can not be updated.');
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
        if ($this->category->find($id)->delete()) {
            $message = 'Category deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['nav-category', 'changeStatus']);
        $data = $this->category->find($request->get('id'));
        $status = $data->is_active == 0 ? 1 : 0;
        $message = $data->is_active == 0 ? 'Category is published.' : 'Category is unpublished.';
        $this->category->changeStatus($data->id, $status);
        $this->category->update($data->id, array('updated_by' => auth()->id()));
        $updated = $this->category->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->category->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully re-ordered.'], 200);
    }
}
