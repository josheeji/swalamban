<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Doctor\DoctorStoreRequest;
use App\Http\Requests\Admin\Doctor\DoctorUpdateRequest;
use App\Repositories\DepartmentRepository;
use App\Repositories\DoctorRepository;
use Illuminate\Http\Request;
use App\Helper\MediaHelper;

class DoctorController extends Controller
{
    public $title = 'Doctors';

    protected $doctor;
    protected $department;

    public function __construct(
        DoctorRepository $doctor,
        DepartmentRepository $department
    ) {
        $this->doctor = $doctor;
        $this->department = $department;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['doctor', 'view']);
        $title = $this->title;
        $doctors = $this->doctor->orderBy('display_order', 'desc')->orderBy('created_at', 'desc')->paginate('10');
        return view('admin.doctor.index', compact('title', 'doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['doctor', 'add']);
        $title = 'Add Doctor';
        $departments = $this->department->departmentList();
        return view('admin.doctor.create', compact('title', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['doctor', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'doctor');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if ($this->doctor->create($data)) {
            return redirect()->route('admin.doctor.index')->with('flash_notice', 'Doctor added successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Doctor can not be added.');
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
        $this->authorize('master-policy.perform', ['doctor', 'edit']);
        $doctor = $this->doctor->find($id);
        $title = 'Edit - ' . $doctor->getFullname();
        $departments = $this->department->departmentList();
        return view('admin.doctor.edit', compact('title', 'doctor', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['doctor', 'edit']);
        $doctor = $this->doctor->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($doctor->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'doctor');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['updated_by'] = auth()->id();
        if ($this->doctor->update($doctor->id, $data)) {
            return redirect()->route('admin.doctor.index')->with('flash_notice', 'Doctor updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Doctor can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['doctor', 'delete']);
        $doctor = $this->doctor->find($id);
        if ($this->doctor->destroy($doctor->id)) {
            if (!empty($doctor->image)) {
                MediaHelper::destroy($doctor->image);
            }
            $message = 'Doctor deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['doctor', 'changeStatus']);
        $doctor = $this->doctor->find($request->get('id'));
        if ($doctor->is_active == 0) {
            $status = 1;
            $message = 'Doctor is published.';
        } else {
            $status = 0;
            $message = 'Doctor is unpublished.';
        }

        $this->doctor->changeStatus($doctor->id, $status);
        $this->doctor->update($doctor->id, array('updated_by' => auth()->id()));
        $updated = $this->doctor->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->doctor->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
