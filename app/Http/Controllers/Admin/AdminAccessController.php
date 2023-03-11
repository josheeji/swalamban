<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ModuleRepository;
use App\Repositories\AdminAccessRepository;
use App\Repositories\AdminTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminAccessController extends Controller
{

    protected $adminType;

    protected $adminAccess;

    protected $module;

    public function __construct(
        AdminTypeRepository $adminType,
        AdminAccessRepository $adminAccess,
        ModuleRepository $module
    ) {
        $this->adminType = $adminType;
        $this->adminAccess = $adminAccess;
        $this->module = $module;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['module'] = $this->module->where('show_in_menu', 1)->orderBy('name', 'asc')->get();
        $data['adminType'] = $this->adminType->findWith($id, 'access');
        $data['access'] = ($data['adminType']->access)->groupBy('module_id');
        $moduleList = [];
        $moduleListById = [8, 34, 76];
        foreach ($data['module'] as $module) {
            if (in_array($module->alias, $moduleList) && !in_array($module->id, $moduleListById)) {
                $access = $this->adminAccess->where('module_id', $module->id)->get();
                if ($access) {
                    foreach ($access as $del) {
                        $del->delete();
                    }
                }
            } else {
                $moduleList[] = $module->alias;
            }
        }
        return view('admin.adminAccess.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($adminTypeId, Request $request)
    {
        $old_data = $this->adminAccess->where('admin_type_id', $adminTypeId)->get();

        $modules = $this->module->model()->pluck('id')->toArray();

        $new_access = [];

        for ($i = 0; $i < count($modules); $i++) {
            $item = [];
            $item['admin_type_id'] = $adminTypeId;
            $item['module_id'] = $modules[$i];

            if ($request->has('view')) {
                if (array_key_exists($modules[$i], $request->view)) {
                    $item['view'] = 1;
                } else {
                    $item['view'] = 0;
                }
            }

            if ($request->has('add')) {
                if (array_key_exists($modules[$i], $request->add)) {
                    $item['add'] = 1;
                } else {
                    $item['add'] = 0;
                }
            }

            if ($request->has('edit')) {
                if (array_key_exists($modules[$i], $request->edit)) {
                    $item['edit'] = 1;
                } else {
                    $item['edit'] = 0;
                }
            }

            if ($request->has('delete')) {
                if (array_key_exists($modules[$i], $request->delete)) {
                    $item['delete'] = 1;
                } else {
                    $item['delete'] = 0;
                }
            }

            if ($request->has('changeStatus')) {
                if (array_key_exists($modules[$i], $request->changeStatus)) {
                    $item['changeStatus'] = 1;
                } else {
                    $item['changeStatus'] = 0;
                }
            }

            $new_access[] = $item;
        }


        $this->adminAccess->model()->where('admin_type_id', $adminTypeId)->delete();

        try {
            $this->adminAccess->model()->insert($new_access);
            return redirect()->route('admin.admin-access.create', [$adminTypeId])
                ->with('flash_notice', 'Access updated successfully');
        } catch (\Exception $e) {
            $old_insert = [];

            foreach ($old_data as $role) {
                $item = [];
                $item['admin_type_id'] = $role->admin_type_id;
                $item['module_id'] = $role->module_id;
                $item['view'] = $role->view;
                $item['add'] = $role->add;
                $item['edit'] = $role->edit;
                $item['delete'] = $role->delete;
                $item['changeStatus'] = $role->changeStatus;
                $old_insert[] = $item;
            }

            $this->adminAccess->model()->insert($old_insert);

            return redirect()->route('admin.admin-access.create', [$adminTypeId])
                ->with('flash_error', 'Something went wrong during the operation.');
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
    public function destroy($id)
    {
        //
    }
}
