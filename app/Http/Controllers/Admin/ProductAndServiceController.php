<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAndService\ProductAndServiceStoreRequest;
use Illuminate\Http\Request;
use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;

class ProductAndServiceController extends Controller
{
    protected $post, $postCategory;
    public $title = 'Products and Services';

    public function __construct(PostRepository $post, PostCategoryRepository $postCategory)
    {
        $this->post = $post;
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
        $dataProvider = $this->post->where('type', ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE)->orderby('display_order', 'desc')->get();
        return view('admin.productAndService.productAndService.index', compact('title', 'dataProvider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $categories = $this->postCategory->where('type', ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE)->where('is_active', 1)->orderby('display_order', 'asc')->get();
        return view('admin.productAndService.productAndService.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAndServiceStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['post', 'add']);
        $data = $request->except(['feature_image', '_banner_image']);
        if ($request->hasFile('feature_image')) {
            $filelocation = MediaHelper::upload($request->file('feature_image'), 'product-and-service');
            $data['feature_image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner_image')) {
            $filelocation = MediaHelper::upload($request->file('banner_image'), 'product-and-service', true);
            $data['banner_image'] = $filelocation['storage'];
        }
        $data['type'] = ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->post->create($data)) {
            return redirect()->route('admin.products-and-services.index')->with('flash_notice', 'Product and Service added successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Product and Service can not be added.');
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
        $this->authorize('master-policy.perform', ['post', 'edit']);
        $model = $this->post->find($id);
        $title = 'Edit - ' . $model->title;
        $categories = $this->postCategory->where('type', ConstantHelper::POST_TYPE_PRODUCT_AND_SERVICE)->where('is_active', 1)->get();
        return view('admin.productAndService.productAndService.edit', compact('title', 'model', 'categories'));
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
        $this->authorize('master-policy.perform', ['post', 'edit']);
        $model = $this->post->find($id);
        $data = $request->except(['feature_image', '_token', '_method', 'banner_image']);
        if ($request->hasFile('feature_image')) {
            MediaHelper::destroy($model->feature_image);
            $filelocation = MediaHelper::upload($request->file('feature_image'), 'product-and-service');
            $data['feature_image'] = $filelocation['storage'];
        }

        if ($request->hasFile('banner_image')) {
            MediaHelper::destroy($model->banner_image);
            $filelocation = MediaHelper::upload($request->file('banner_image'), 'product-and-service', true);
            $data['banner_image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if ($this->post->update($model->id, $data)) {
            return redirect()->route('admin.products-and-services.index')->with('flash_notice', 'Product and Service updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Product and Service can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['post', 'delete']);
        $post = $this->post->find($id);
        if ($this->post->destroy($post->id)) {
            if (!empty($post->feature_image)) {
                MediaHelper::destroy($post->feature_image);
            }
            if (!empty($post->banner_image)) {
                MediaHelper::destroy($post->banner_image);
            }
            $message = 'Product and Service deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['post', 'changeStatus']);
        $post = $this->post->find($request->get('id'));
        $status = $post->is_active == 0 ? 1 : 0;
        $message = $post->is_active == 0 ? 'Published' : 'Unpublished';
        $this->post->changeStatus($post->id, $status);
        $updated = $this->post->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->post->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
