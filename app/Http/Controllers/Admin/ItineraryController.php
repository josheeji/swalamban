<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ItineraryStoreRequest;
use App\Http\Requests\Admin\ItineraryUpdateRequest;
use App\Repositories\ItineraryRepository;
use App\Repositories\PackageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItineraryController extends Controller
{
    protected $itinerary;
    public  function __construct(ItineraryRepository $itinerary,
                                 PackageRepository $package)
    {
        $this->itinerary = $itinerary;
        $this->package = $package;
        auth()->shouldUse('admin');
    }

    public function index($id)
    {
        $this->authorize('master-policy.perform',['itinerary','view']);
        $perpage = '100';
        $itineraries  = $this->itinerary->where('package_id',$id)->paginate($perpage);
        return view('admin.itinerary.index')
        ->withItineraries($itineraries)
        ->withPackage($this->package->find($id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('master-policy.perform',['itinerary','add']);
        return view('admin.itinerary.create')
        ->withPackage($this->package->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItineraryStoreRequest $request)
    {
        $this->authorize('master-policy.perform',['itinerary','add']);
       $packageid = $request->package_id;
        $data = $request->except(['image']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','', $image);
            $image = str_replace('','+',$image);
            $imageData = base64_decode($image);
            $data['image'] = 'itinerary/'.$saveName.'.png';
            Storage::put($data['image'], $imageData);

        }
       $data['slug'] =Str::slug($request->title);
       $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->itinerary->create($data)){
            return redirect()->route('admin.itinerary.index',$packageid)->with('flash_notice','Itinerary Created SuccessFully');

        }
        return redirect()->back()->withInput()->with('flash_notice','flash_notice','Itinerary can not be Created ');

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
        $this->authorize('master-policy.perform',['itinerary','add']);
        $itinerary = $this->itinerary->find($id);
        $package = $itinerary->id;
        return view('admin.itinerary.edit')
        ->withItinerary($itinerary);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItineraryUpdateRequest $request,$package_id, $itinerary_id)
    {
        $this->authorize('master-policy.perform',['itinerary','edit']);
        $data = $request->except(['image','_token','_method']);
        $itinerary = $this->itinerary->find($itinerary_id);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','', $image);
            $image = str_replace('','+',$image);
            $imageData = base64_decode($image);
            $data['image'] = 'itinerary/'.$saveName.'.png';
            Storage::put($data['image'], $imageData);
            if(Storage::exists($itinerary->image)){
                Storage::delete($itinerary->image);
            }

        }
        $data['slug'] =Str::slug($request->title);
        $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->itinerary->update($itinerary_id,$data)) {
            return redirect()->route('admin.itinerary.index',[$package_id])->with('flash_notice', 'Itinerary Updated SuccessFully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Itinerary can not be Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $package_id, $itinerary_id)
    {
        $this->authorize('master-policy.perform', ['itinerary', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:itineraries,id',
        ]);
        $itinerary = $this->itinerary->find($itinerary_id);
        $this->itinerary->destroy($itinerary->id);
        $message = 'Item deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
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
