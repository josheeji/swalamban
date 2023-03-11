<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ImageResizeStoreRequest;
use App\Http\Requests\Admin\ImageResizeUpdateRequest;
use App\Repositories\ImageResizeRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageresizeController extends Controller
{
    protected $image_resize;
    public  function __construct(ImageResizeRepository $image_resize)
                                
    {
        $this->image_resize = $image_resize;
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform',['imageresize','view']);
        $perpage = '15';
        $imageresizes  = $this->image_resize->paginate($perpage);
        return view('admin.image_resize.index')
        ->withImageresizes($imageresizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform',['imageresize','add']);
        return view('admin.image_resize.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageResizeStoreRequest $request)
    {
       $this->authorize('master-policy.perform',['imageresize','add']);
       $data = $request->all();
       $data['slug'] =Str::slug($request->title);
       $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->image_resize->create($data)){
            return redirect()->route('admin.imageresize.index')->with('flash_notice','Image resize Created SuccessFully');

        }
        return redirect()->back()->withInput()->with('flash_notice','flash_notice','Image resize can not be Created ');

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
        $this->authorize('master-policy.perform',['imageresize','add']);
        $imageresize = $this->image_resize->find($id);
        return view('admin.image_resize.edit')->withImageresize($imageresize);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImageResizeStoreRequest $request, $id)
    {
        $this->authorize('master-policy.perform',['imageresize','edit']);
        $image_resize = $this->image_resize->find($id);
        $data = $request->except(['_token','_method']);
        $data['slug'] =Str::slug($request->title);
        $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->image_resize->update($image_resize->id,$data)) {
            return redirect()->route('admin.imageresize.index')->with('flash_notice', 'Image Resize Updated SuccessFully');
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['imageresize', 'delete']);
        $image_resize = $this->image_resize->find($id);
        $image_resize = $this->image_resize->find($request->get('id'));
        if($this->image_resize->destroy($image_resize->id)){
            $message = 'Image Resize deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 200);
    }
    
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['itinerary', 'changeStatus']);
        $itinerary = $this->itinerary->find($request->get('id'));
        if ($itinerary->is_active == 0) {
            $status = '1';
            $message = 'Itinerary with title "' . $itinerary->title . '" is published.';
        } else {
            $status = '0';
            $message = 'Itinerary with title "' . $itinerary->title . '" is unpublished.';
        }
        $this->itinerary->changeStatus($itinerary->id, $status);
        $this->itinerary->update($itinerary->id, array('is_active' => $status));
        $updated = $this->itinerary->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
