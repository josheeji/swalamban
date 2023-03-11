<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAndService\ProductAndServiceCategoryStoreRequest;
use App\Http\Requests\Admin\ProductAndService\ProductAndServiceCategoryUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories\PostCategoryRepository;

class ProductAndServiceCategoryController extends Controller
{
    protected $postCategory;
    public $title = 'Product and Service Categories';

    public function __construct(PostCategoryRepository $postCategory)
    {
        $this->postCategory = $postCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $dataProvider = $this->postCategory->where('type', ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE)->orderby('display_order', 'desc')->get();
        return view('admin.productAndService.category.index', compact('title', 'dataProvider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $categories = $this->postCategory->where('is_active', 1)->get();
        return view('admin.productAndService.category.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAndServiceCategoryStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['post-category', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'product-and-service');
            $data['image'] = $filelocation['storage'];
        }
        $data['type'] = ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->postCategory->create($data)) {
            return redirect()->route('admin.products-and-services-category.index')->with('flash_notice', 'Category added successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Category can not be added.');
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
        $this->authorize('master-policy.perform', ['post-category', 'edit']);
        $category = $this->postCategory->find($id);
        $categories = $this->postCategory->where('is_active', 1)->get();
        $title = 'Edit - ' . $category->title;
        return view('admin.productAndService.category.edit', compact('title', 'category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductAndServiceCategoryUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['post-category', 'edit']);
        $category = $this->postCategory->find($id);
        $data = $request->except(['image', '_token', '_method']);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($category->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'product-and-service');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if ($this->postCategory->update($category->id, $data)) {
            return redirect()->route('admin.products-and-services-category.index')->with('flash_notice', 'Category updated successfully');
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
        $this->authorize('master-policy.perform', ['post-category', 'delete']);
        $category = $this->postCategory->find($id);
        if ($this->postCategory->destroy($category->id)) {
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
        $this->authorize('master-policy.perform', ['post-category', 'changeStatus']);
        $category = $this->postCategory->find($request->get('id'));
        $status = $category->is_active == 0 ? 1 : 0;
        $message = $category->is_active == 0 ? 'Published' : 'Unpublished';
        $this->postCategory->changeStatus($category->id, $status);
        $updated = $this->postCategory->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->postCategory->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
