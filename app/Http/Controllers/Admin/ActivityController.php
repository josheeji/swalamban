<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ActivityStoreRequest;
use App\Http\Requests\Admin\ActivityUpdateRequest;

use App\Repositories\ActivityRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $activity;
    public  function __construct(ActivityRepository $activity)
    {
        $this->activity = $activity;
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform',['activity','view']);
        $perpage = '1000';
       $activities  = $this->activity->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate($perpage);
        return view('admin.activity.index')->withActivities($activities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform',['activity','add']);
        return view('admin.activity.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityStoreRequest $request)
    {
        $this->authorize('master-policy.perform',['activity','add']);
        $data = $request->except(['image']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','', $image);
            $image = str_replace('','+',$image);
            $imageData = base64_decode($image);
            $data['image'] = 'activity/'.$saveName.'.png';
            Storage::put($data['image'], $imageData);

        }
        $data['slug'] =Str::slug($request->title);
       $data['is_active'] = isset($request->is_active) ? 1:0;
       if($this->activity->create($data)){
             return redirect()->route('admin.activity.index')->with('flash_notice','Activity Created SuccessFully');

        }
        return redirect()->back()->withInput()->with('flash_notice','flash_notice','Activity can not be Created ');

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
        $this->authorize('master-policy.perform',['activity','add']);
        $activity = $this->activity->find($id);
        return view('admin.activity.edit')->withActivity($activity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform',['activity','add']);
        $activity = $this->activity->find($id);
        $data = $request->except(['image','_token','_method']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','', $image);
            $image = str_replace('','+',$image);
            $imageData = base64_decode($image);
            $data['image'] = 'activity/'.$saveName.'.png';
            Storage::put($data['image'], $imageData);
            if(Storage::exists($activity->image)){
                Storage::delete($activity->image);
            }

        }
        $data['slug'] =Str::slug($request->title);
        $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->activity->update($activity->id,$data)) {
            return redirect()->route('admin.activity.index')->with('flash_notice', 'Activity Updated SuccessFully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Activity can not be Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['activity', 'delete']);
        $activity = $this->activity->find($request->get('id'));
        
        if($this->activity->destroy($activity->id)){
            $message = 'Activity deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 200);
    }
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['activity', 'changeStatus']);
        $activity = $this->activity->find($request->get('id'));
        if ($activity->is_active == 0) {
            $status = '1';
            $message = 'Activity with title "' . $activity->title . '" is published.';
        } else {
            $status = '0';
            $message = 'activity with title "' . $activity->title . '" is unpublished.';
        }
        $this->activity->changeStatus($activity->id, $status);
        $this->activity->update($activity->id, array('is_active' => $status));
        $updated = $this->activity->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request){
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i=0; $i < count($exploded) ; $i++) {
            $this->activity->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
