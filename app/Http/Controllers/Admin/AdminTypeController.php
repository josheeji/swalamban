<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserTypeRequest;
use App\Repositories\AdminTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTypeController extends Controller
{

    public $title = 'Roles';

    protected $adminType;

    public function __construct(
        AdminTypeRepository $adminType
    ) {
        $this->adminType = $adminType;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $adminTypes = $this->adminType->all();

        return view('admin.adminType.index')
            ->withTitle($title)
            ->withAdminTypes($adminTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add User Type';

        return view('admin.adminType.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserTypeRequest $request)
    {
        $data = $request->all();
        $data['name'] = $request->title;
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $adminType = $this->adminType->create($data);

        return redirect()->route('admin.admin-type.index')
            ->with('flash_notice', 'User Type Created Successfully.');
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
        $title = $this->title;
        $adminType = $this->adminType->find($id);

        return view('admin.adminType.edit', compact('title', 'adminType'));
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
        $data = $request->all();
        $data['name'] = $request->title;
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;

        $adminType = $this->adminType->update($id, $data);

        return redirect()->route('admin.admin-type.index')
            ->with('flash_notice', 'User Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required|exists:admin_types,id',
        ]);
        $adminType = $this->adminType->find($request->get('id'));
        $this->adminType->destroy($adminType->id);
        $message = 'User Type deleted successfully.';

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        $adminType = $this->adminType->find($request->get('id'));
        if ($adminType->is_active == 0) {
            $status = 1;
            $message = 'User Type with title "' . $adminType->name . '" is published.';
        } else {
            $status = 0;
            $message = 'User Type with title "' . $adminType->name . '" is unpublished.';
        }

        $this->adminType->changeStatus($adminType->id, $status);
        $updated = $this->adminType->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
