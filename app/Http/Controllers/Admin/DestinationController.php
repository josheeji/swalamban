<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Image;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Http\Controllers\Controller;
use App\Repositories\DestinationRepository;
use App\Http\Requests\Admin\DestinationStoreRequest;
use App\Http\Requests\Admin\DestinationUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public $title = 'Destination';
    /**
     * The DestinationRepository implementation.
     *
     * @var $destination
     */
    protected $destination;

    /**
     * Create a new controller instance.
     *
     * @param  DestinationRepository $destination
     */
    public function __construct(DestinationRepository $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        abort_if(Gate::denies('master-policy.perform', ['destination', 'view']), 403);
        $perpage = '100';
        $title = $this->title;
        $destinations = $this->destination->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate($perpage);
        return view('admin.destination.index', compact('title', 'destinations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        abort_if(Gate::denies('master-policy.perform', ['destination', 'add']), 403);

        $title = 'Create Destination';
        return view('admin.destination.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(DestinationStoreRequest $request)
    {
         $this->authorize('master-policy.perform', ['destination', 'add']);
         $data = $request->except(['image']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis').Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','',$image);
            $image= str_replace('','+',$image);
            $imageData= base64_decode($image);
            $data['image'] = 'destination/'.$saveName.'.png';
            Storage::put($data['image'],$imageData);
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if($this->destination->create($data)){
            return redirect()->route('admin.destination.index')->with('flash_notice','Destinations is created Successfully');

        }
        return redirect()->back()->withInput()->with('flash_notice','Destinations can not be created ');

    }

    public function edit(Request $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['destination', 'edit']), 403);

        $title = 'Edit Destination';
        $destination = $this->destination->find($id);
        return view('admin.destination.edit', compact('title', 'destination'));

    }


    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['destination', 'changeStatus']), 403);

        $destination = $this->destination->find($request->get('id'));
        if ($destination->is_active == 0) {
            $status = 1;
            $message = 'Destination with name "' . $destination->name . '" is published.';
        } else {
            $status = 0;
            $message = 'Destination with name "' . $destination->name . '" is unpublished.';
        }
        $this->destination->update($destination->id, ['is_active' => $status]);
        $updated = $this->destination->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }


    public function update(DestinationUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['destination', 'edit']);
        $destination = $this->destination->find($id);
        $data = $request->except(['image']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis').Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','',$image);
            $image= str_replace('','+',$image);
            $imageData= base64_decode($image);
            $data['image'] = 'destination/'.$saveName.'.png';
            Storage::put($data['image'],$imageData);
        }
            $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if($this->destination->update($destination->id,$data)){
            return redirect()->route('admin.destination.index')->with('flash_notice','Destination is Update Successfully');

        }
        return redirect()->back()->withInput()->with('flash_notice','Destination can not be Update ');
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['destination', 'delete']), 403);

        $this->validate($request, [
            'id' => 'required|exists:destinations,id',
        ]);
        $district = $this->destination->find($request->get('id'));
        $this->destination->destroy($district->id);
        $message = 'Destination is deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function sort(Request $request){
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i=0; $i < count($exploded) ; $i++) {
            $this->destination->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
