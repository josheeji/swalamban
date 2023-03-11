<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageCategory\PackageCategoryStoreRequest;
use App\Http\Requests\Admin\PackageCategory\PackageCategoryUpdateRequest;
use App\Repositories\PackageCategoryRepository;
use Illuminate\Http\Request;

class PackageCategoryController extends Controller
{

    public $title = 'Package Categories';

    protected $package_category;

    public function __construct(
        PackageCategoryRepository $package_category
    ) {
        $this->package_category = $package_category;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['package-category', 'view']);
        $title = $this->title;
        $categories = $this->package_category->orderBy('display_order', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.packageCategory.index', compact('title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['package-category', 'add']);
        $title = 'Add Package Category';
        $categories = $this->package_category->categoryList();
        return view('admin.packageCategory.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageCategoryStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['package-category', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'package-category');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->package_category->create($data)) {
            return redirect()->route('admin.package-categories.index')->with('flash_notice', 'Successfully created new category.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'New category can not be created.');
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
        $this->authorize('master-policy.perform', ['package-category', 'edit']);
        $category = $this->package_category->find($id);
        $title = 'Edit Package Category - ' . $category->title;
        $categories = $this->package_category->categoryList();
        return view('admin.packageCategory.edit', compact('title', 'category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackageCategoryUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['package-category', 'edit']);
        $category = $this->package_category->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($category->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'package-category');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['updated_by'] = auth()->id();
        if ($this->package_category->update($category->id, $data)) {
            return redirect()->route('admin.package-categories.index')->with('flash_notice', 'Category updated successfully');
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
        $this->authorize('master-policy.perform', ['package-category', 'delete']);
        $category = $this->package_category->find($id);
        if ($this->package_category->destroy($category->id)) {
            if (!empty($category->image)) {
                MediaHelper::destroy($category->image);
            }
            $message = 'Category deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['package-category', 'changeStatus']);
        $category = $this->package_category->find($request->get('id'));
        if ($category->is_active == 0) {
            $status = 1;
            $message = 'Category is published.';
        } else {
            $status = 0;
            $message = 'Category is unpublished.';
        }

        $this->package_category->changeStatus($category->id, $status);
        $this->package_category->update($category->id, array('updated_by' => auth()->id()));
        $updated = $this->package_category->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->package_category->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
