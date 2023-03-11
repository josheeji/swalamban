<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Associate\AssociateStoreRequest;
use App\Http\Requests\Admin\Associate\AssociateUpdateRequest;
use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class AssociateController extends Controller
{

    protected $post, $postCategory;
    public $title = 'Associates';

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
        $dataProvider = $this->post->where('type', ConstantHelper::POST_TYPE_ASSOCIATE)->orderby('display_order', 'desc')->get();
        return view('admin.associate.index', compact('title', 'dataProvider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        return view('admin.associate.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssociateStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['post', 'add']);
        $data = $request->except(['feature_image']);
        if ($request->hasFile('feature_image')) {
            $filelocation = MediaHelper::upload($request->file('feature_image'), 'associate');
            $data['feature_image'] = $filelocation['storage'];
        }
        $data['type'] = ConstantHelper::POST_TYPE_ASSOCIATE;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->post->create($data)) {
            return redirect()->route('admin.associates.index')->with('flash_notice', 'Associate added successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Associate can not be added.');
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
        $associate = $this->post->find($id);
        $title = 'Edit - ' . $associate->title;
        return view('admin.associate.edit', compact('title', 'associate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AssociateUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['post', 'edit']);
        $associate = $this->post->find($id);
        $data = $request->except(['feature_image', '_token', '_method']);
        if ($request->hasFile('feature_image')) {
            MediaHelper::destroy($associate->feature_image);
            $filelocation = MediaHelper::upload($request->file('feature_image'), 'associate');
            $data['feature_image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if ($this->post->update($associate->id, $data)) {
            return redirect()->route('admin.associates.index')->with('flash_notice', 'Associate updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Associate can not be updated.');
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
            $message = 'Associate deleted successfully.';
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
