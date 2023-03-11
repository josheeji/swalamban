<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoctorTimeSlot\DoctorTimeSlotStoreRequest;
use App\Repositories\DoctorRepository;
use App\Repositories\DoctorTimeSlotRepository;
use Illuminate\Http\Request;

class DoctorTimeSlotController extends Controller
{
    public $title = 'Time Slots';

    protected $doctor;
    protected $time_slot;

    public function __construct(
        DoctorRepository $doctor,
        DoctorTimeSlotRepository $time_slot
    ) {
        $this->doctor = $doctor;
        $this->time_slot = $time_slot;
        auth()->shouldUse('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'view']);
        $title = $this->title;
        $doctor = $this->doctor->find($request->get('doctor-id'));
        $time_slots = $this->time_slot->where('doctor_id', $doctor->id)->orderBy('created_at', 'desc')->paginate('10');
        return view('admin.doctorTimeSlot.index', compact('title', 'doctor', 'time_slots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'add']);
        $title = 'Add Time Slot';
        $doctor = $this->doctor->find($request->get('doctor-id'));
        return view('admin.doctorTimeSlot.create', compact('title', 'doctor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorTimeSlotStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'add']);
        $data = $request->except(['image']);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->time_slot->create($data)) {
            return redirect()->route('admin.doctor-time-slot.index', ['doctor-id' => $data['doctor_id']])->with('flash_notice', 'Time slot added successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Time slot can not be added.');
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
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'edit']);
        $time_slot = $this->time_slot->find($id);
        $title = 'Edit - ' . $this->title;
        $doctor = $time_slot->doctor;
        return view('admin.doctorTimeSlot.edit', compact('title', 'doctor', 'time_slot'));
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
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'edit']);
        $time_slot = $this->time_slot->find($id);
        $data = $request->except(['image']);
        $data['is_fulltime'] = isset($request['is_fulltime']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        if ($this->time_slot->update($time_slot->id, $data)) {
            return redirect()->route('admin.doctor-time-slot.index', ['doctor-id' => $data['doctor_id']])->with('flash_notice', 'Time slot updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Time slot can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'delete']);
        $time_slot = $this->time_slot->find($id);
        if ($this->time_slot->destroy($time_slot->id)) {
            $message = 'Time slot deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['doctor-time-slot', 'changeStatus']);
        $time_slot = $this->time_slot->find($request->get('id'));
        if ($time_slot->is_active == 0) {
            $status = 1;
            $message = 'Time slot is published.';
        } else {
            $status = 0;
            $message = 'Time slot is unpublished.';
        }

        $this->time_slot->changeStatus($time_slot->id, $status);
        $this->time_slot->update($time_slot->id, array('updated_by' => auth()->id()));
        $updated = $this->time_slot->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
