<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserCreateRequest;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AdminTypeRepository;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;

class AdminListController extends Controller
{

    public $title = 'Admin List';

    protected $admin, $adminType;

    public function __construct(AdminRepository $admin, AdminTypeRepository $adminType)
    {
        $this->admin = $admin;
        $this->adminType = $adminType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($adminTypeId)
    {
        $title = $this->title;
        $admins = $this->admin->where('admin_type_id', $adminTypeId)->get();

        return view('admin.adminList.index', compact('title', 'admins', 'adminTypeId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($adminTypeId)
    {
        $title = 'Create Admin';

        return view('admin.adminList.create', compact('title', 'adminTypeId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($adminTypeId, UserCreateRequest $request)
    {
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $data['username'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $admin = $this->admin->create($data);

        return redirect()->route('admin.admin-list.index', [$adminTypeId])
            ->with('flash_notice', 'Admin user Created Successfully.');
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
        $admin = $this->admin->find($id);
        $adminType = $this->adminType->orderBy('name', 'asc')->get();

        return view('admin.adminList.profile')->withAdmin($admin)->withAdminType($adminType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserCreateRequest $request, $id)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $adminType = $this->admin->update($id, $data);

        return redirect()->route('admin.admin-list.index', [$request->admin_type_id])
            ->with('flash_notice', 'Admin user Updated successfully Successfully.');
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
            'id' => 'required|exists:admins,id',
        ]);
        $admin = $this->admin->find($request->get('id'));
        $this->admin->destroy($admin->id);
        $message = 'Admin user deleted successfully.';

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        $admin = $this->admin->find($request->get('id'));
        $status = $admin->is_active == 0 ? 1 : 0;
        $message = $admin->is_active == 0 ? 'Admin user with email "' . $admin->email . '" is published.' : 'Admin user with email "' . $admin->email . '" is unpublished.';
        $this->admin->changeStatus($admin->id, $status);
        $updated = $this->admin->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
    public function resetPassword(Request $request)
    {
        $messages = [
            'oldpassword.required' => 'Please enter current password',
            'newpassword.required' => 'Please enter new password',
            'confirmpassword.required' => 'Please enter confirm password',
            'confirmpassword.same' => 'Confirm password should match with new password'
        ];

        $rules = array(
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required|same:newpassword',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'false', 'errors' => $validator->errors()]);
        } else {
            $user = Auth::guard('admin')->user();
            if (Hash::check($request->oldpassword, Auth::guard('admin')->user()->password)) {
                $data['password'] = bcrypt($request->newpassword);
                $message = 'Password updated successfully';
                $admin = $this->admin->update($user->id, $data);

                return response()->json(['status' => 'ok', 'message' => $message, 'response' =>  $admin], 200);
            } else {
                $message = 'Old password is incorrect';

                return response()->json(['error' => 'false', 'message' => $message], 200);
            }
        }
    }
}
