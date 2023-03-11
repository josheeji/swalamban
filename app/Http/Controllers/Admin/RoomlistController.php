<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RoomlistRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class RoomlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $title = 'Roomlist';

    protected $roomlist;
    public function __construct(RoomlistRepository $roomlist)
    {
        $this->roomlist = $roomlist;
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform',['room-list','view']);
       $title = $this->title;
        $roomlists = $this->roomlist->orderBy('created_at', 'desc')->get();
        return view('admin.roomlist.index')->withRoomlists($roomlists)->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform',['room-list','add']);
        return view('admin.roomlist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform',['room-list','add']);
        $rules = [
            'name' => 'required',
            'cover_image' => 'required',
            'is_active' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'number_of_rooms' => 'required'
        ];

        $messages = [
            'name.required' => 'Please Insert The Name',
            'is_active.required' => 'Please select the Publish',
            'description.required' => 'Please Insert The Description',
            'short_description.required' => 'Please Insert The Short Description',
            'cover_image.required' => 'Please Upload valid Image File'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->except(['image']);
            if($request->get('cover_image')){
                $saveName = sha1(date('YmdHis').Str::random(3));
                $image = $request->get('cover_image');
                $image = str_replace('data:image/png;base64','',$image);
                $image= str_replace('','+',$image);
                $imageData= base64_decode($image);
                $data['cover_image'] = 'room/'.$saveName.'.png';
                Storage::put($data['cover_image'],$imageData);
            }
            $data['is_active'] = isset($request['is_active']) ? 1 : 0;

            if($this->roomlist->create( $data)){
                return redirect()->route('admin.roomlist.index')->with('flash_notice','Roomlist is created Successfully');
            }
            return redirect()->back()->withInput()->with('flash_notice','Roomlist can not be create');

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
        $this->authorize('master-policy.perform',['room-list','edit']);
        $roomlist = $this->roomlist->findOrfail($id);
        return view('admin.roomlist.edit')->withRoomlist($roomlist);
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
        $this->authorize('master-policy.perform',['room-list','edit']);
        $rules = [
            'name' => 'required',
            'cover_image' => 'nullable',

            'description' => 'required',
            'short_description' => 'required',
            'number_of_rooms' => 'required'
        ];

        $messages = [
            'name.required' => 'Please Insert The Name',
            'description.required' => 'Please Insert The Description',
            'short_description.required' => 'Please Insert The Short Description',
            'cover_image.required' => 'Please Upload valid Image File'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $data = $request->except(['image','_token','_method']);
            $roomlist = $this->roomlist->find($id);
            if($request->get('cover_image')){
                $saveName = sha1(date('YmdHis').Str::random(3));
                $image = $request->get('cover_image');
                $image = str_replace('data:image/png;base64','',$image);
                $image= str_replace('','+',$image);
                $imageData= base64_decode($image);
                $data['cover_image'] = 'room/'.$saveName.'.png';
                Storage::put($data['cover_image'],$imageData);
            }
            $data['is_active'] = isset($request['is_active']) ? 1 : 0;

            if($this->roomlist->update(  $roomlist->id, $data)){
                return redirect()->route('admin.roomlist.index')->with('flash_notice','Roomlist is Updated Successfully');
            }
            return redirect()->back()->withInput()->with('flash_notice','Roomlist can not be Update');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){

        $this->authorize('master-policy.perform',['room-list','delete']);
        $roomlist = $this->roomlist->findOrfail($id);
        if($this->roomlist->destroy($roomlist->id)){
            $message = 'Roomlist deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
    public function changeStatus(Request $request){
        $this->authorize('master-policy.perform',['room-list','changeStatus']);
        $roomlist = $this->roomlist->find($request->get('id'));
        if ($roomlist->is_active == 0) {
            $status = '1';
            $message = 'Roomlist with title "' . $roomlist->title . '" is published.';
        } else {
            $status = '0';
            $message = 'Roomlist with title "' . $roomlist->title . '" is unpublished.';
        }
        $this->roomlist->changeStatus(  $roomlist->id, $status);
        $this->roomlist->update($roomlist->id,['is_active' =>  $status ]);
        $updated = $this->roomlist->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
