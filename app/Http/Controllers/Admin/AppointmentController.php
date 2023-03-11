<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\DoctorTimeSlotRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public $title = 'Appointments';

    protected $appointment, $department, $doctor, $time_slot;

    public function __construct(
        AppointmentRepository $appointment,
        DepartmentRepository $department,
        DoctorRepository $doctor,
        DoctorTimeSlotRepository $time_slot

    ) {
        $this->appointment = $appointment;
        $this->department = $department;
        $this->doctor = $doctor;
        $this->time_slot = $time_slot;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['appointment', 'view']);
        $title = $this->title;
        $appointments = $this->appointment->orderBy('created_at', 'desc')->get();
        return view('admin.appointment.index', compact('title', 'appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('master-policy.perform', ['appointment', 'edit']);
        $appointment = $this->appointment->find($id);
        $title = 'Show - Appointment';
        return view('admin.appointment.show', compact('title', 'appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['appointment', 'edit']);
        $appointment = $this->appointment->find($id);
        $title = 'Edit - Appointment';
        $departments = $this->department->departmentList();
        $doctors = $this->doctor->doctorListByDepartment($appointment->department_id);
        $time_slots = $this->time_slot->where('doctor_id', $appointment->doctor_id)->get();
        return view('admin.appointment.edit', compact('title', 'doctors', 'departments', 'time_slots', 'appointment'));
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
    public function destroy($id)
    {
        //
    }
}
