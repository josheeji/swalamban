<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\FixedDepartureStoreRequest;
use App\Http\Requests\Admin\FixedDepartureUpdateRequest;
use App\Http\Controllers\Controller;
use App\Repositories\FixedDepartureRepository;
use App\Repositories\PackageRepository;
use Illuminate\Support\Facades\Storage;
use DateTime;

class FixedDepartureController extends Controller
{
    protected $itinerary,$fixed_departure;
    public  function __construct(FixedDepartureRepository $fixed_departure,
                                 PackageRepository $package)
    {
        $this->fixed_departure = $fixed_departure;
        $this->package = $package;
        auth()->shouldUse('admin');
    }

    public function index($id)
    {
        $this->authorize('master-policy.perform',['departure','view']);
        $perpage = '100';
        $departures  = $this->fixed_departure->where('package_id',$id)->paginate($perpage);
        return view('admin.fixed_departure.index')
        ->withDepartures($departures)
        ->withPackage($this->package->find($id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('master-policy.perform',['departure','add']);
        return view('admin.fixed_departure.create')
        ->withPackage($this->package->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FixedDepartureStoreRequest $request)
    {
        $this->authorize('master-policy.perform',['departure','add']);
        if($this->fixed_departure->create($request->all())){
            return redirect()->route('admin.fixeddeparture.index',[$request->package_id])
                ->with('flash_notice', 'Dates Added Successfully.');
        }else{
            return redirect()->back()->with('flash_error', 'Something went wrong during the operation.');
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
        $this->authorize('master-policy.perform',['departure','add']);
        $title = 'Edit Item';
        $departure = $this->fixed_departure->find($id);
        $package = $departure->package_id;
        return view('admin.fixed_departure.edit')
        ->withDeparture($departure)
        ->withPackage($package)
        ->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FixedDepartureUpdateRequest $request,$package_id, $fixeddeparture_id)
    {
        $this->authorize('master-policy.perform',['departure','edit']);
        $data = $request->except(['image','_token','_method']);
        $data['is_active'] = isset($request->is_active) ? 1:0;
        if($this->fixed_departure->update($fixeddeparture_id,$data)) {
            return redirect()->route('admin.fixeddeparture.index',[$package_id])->with('flash_notice', 'Fixed Departure Updated SuccessFully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Fixed Departure can not be Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $package_id, $fixeddeparture_id)
    {
        $this->authorize('master-policy.perform', ['departure', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:fixed_departures,id',
        ]);
        $fixed_departure = $this->fixed_departure->find($fixeddeparture_id);
        $this->fixed_departure->destroy($fixed_departure->id);
        $message = 'Fixed Departure deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['departure', 'changeStatus']);
        $fixed_departure = $this->fixed_departure->find($request->get('id'));
        if ($fixed_departure->is_active == 0) {
            $status = '1';
            $message = 'fixed_departure with title "' . $fixed_departure->title . '" is published.';
        } else {
            $status = '0';
            $message = 'fixed_departure with title "' . $fixed_departure->title . '" is unpublished.';
        }
        $this->fixed_departure->changeStatus($fixed_departure->id, $status);
        $this->fixed_departure->update($fixed_departure->id, array('is_active' => $status));
        $updated = $this->fixed_departure->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
