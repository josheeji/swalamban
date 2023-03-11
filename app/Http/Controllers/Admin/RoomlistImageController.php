<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RoomImageRepository;
use App\Repositories\RoomlistRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RoomlistImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $roomlist;
    protected $roomimage;
    public function __construct(RoomlistRepository $roomlist, RoomImageRepository $roomimage)
    {
        $this->roomlist = $roomlist;
        $this->roomimage = $roomimage;
        auth()->shouldUse('admin');
    }

    public function index($id)
    {
        $this->authorize('master-policy.perform',['room-list','view']);
        $roomlist = $this->roomlist->find($id);
        $roomimages = $this->roomimage->where('roomlist_id', $id)->get();
        return view('admin.roomlist.image')->withRoomlist($roomlist)->withRoomimages($roomimages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('master-policy.perform',['room-list','add']);
        $roomlist = $this->roomlist->find($id);
        return view('admin.roomlist.uploadImages')->withRoomlist($roomlist);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->authorize('master-policy.perform',['room-list','add']);
        $data = $request->except(['file']);
        $file = $request->file('file');
        $file_name = time()."_".$file->getClientOriginalName();
        $data['roomlist_id'] = $id;
        $data['image'] = 'roomlistImage/'. $id. '/' .$file_name;
        Storage::put('roomlistImage/'.$id.'/'.$file_name, file_get_contents($file->getRealPath()));

        $this->roomimage->create($data);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        $this->authorize('master-policy.perform',['gallery','delete']);
        $roomlistImage = $this->roomimage->find($request->id);

        if($this->roomimage->destroy($roomlistImage->id)) {
            if (Storage::exists($roomlistImage->image)) {
                Storage::delete($roomlistImage->image);
            }
            $message = 'Roomlist Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
