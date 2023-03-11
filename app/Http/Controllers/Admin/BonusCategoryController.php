<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BonusCategoryRepository;
use App\Repositories\BonusRepository;
use Illuminate\Http\Request;

class BonusCategoryController extends Controller
{

    public $title = 'Bonus Categories';
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
    public function index()
    {
        $this->authorize('master-policy.perform', ['bonus-category', 'view']);
        $data = $this->category->orderBy('title', 'asc')->get();
        return view('admin.bonusCategory.index', ['title' => $this->title, 'data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['bonus-category', 'add']);

        return view('admin.bonusCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', ['bonus-category', 'add']);
        $data = $request->except(['_token']);
        if ($category = $this->category->create($data)) {
            return redirect()->route('admin.bonus-category.index')
                ->with('flash_notice', 'Data updated successfully');
        }
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
        $this->authorize('master-policy.perform', ['bonus-category', 'edit']);
        $category = $this->category->find($id);

        return view('admin.bonusCategory.edit', ['title' => $this->title, 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['bonus-category', 'edit']);
        $data = $request->except(['_token']);
        if ($category = $this->category->update($id, $data)) {
            return redirect()->route('admin.bonus-category.index')
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
        $this->authorize('master-policy.perform', ['bonus-category', 'delete']);
        $category = $this->category->find($id);
        if ($this->category->destroy($category->id)) {
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['bonus-category', 'changeStatus']);
        $category = $this->category->find($request->get('id'));
        $status = $category->is_active == 0 ? 1 : 0;
        $message = $category->is_active == 0 ? 'Data is published.' : 'Data is unpublished.';

        $this->category->changeStatus($category->id, $status);
        $this->category->update($category->id, array('updated_by' => auth()->id()));
        $updated = $this->category->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
