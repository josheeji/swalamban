<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Module\ModuleStoreRequest;
use App\Http\Requests\Admin\Module\ModuleUpdateRequest;
use App\Repositories\ModuleRepository;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public $title = 'Modules';

    protected $module;

    public function __construct(
        ModuleRepository $module
    ) {
        $this->module = $module;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $modules = $this->module->orderBy('name', 'asc')->get();

        return view('admin.module.index', compact('title', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;

        return view('admin.module.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleStoreRequest $request)
    {
        if ($this->module->create($request->all())) {

            return redirect()->route('admin.module.index')->with('flash_notice', 'Module added successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Module can not be created.');
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
        $module = $this->module->find($id);
        return view('admin.module.edit', compact('title', 'module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleUpdateRequest $request, $id)
    {
        if ($this->module->update($id, $request->all())) {
            return redirect()->route('admin.module.index')->with('flash_notice', 'Module updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Module can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->module->destroy($id)) {

            return response()->json(['status' => 'ok', 'message' => 'Module deleted successfully.'], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->module->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function toggleMenu(Request $request)
    {
        $module = $this->module->find($request->get('id'));
        $show_in_menu = $module->show_in_menu  == 0 ? 1 : 0;
        $message = $module->show_in_menu == 0 ? 'Added to menu.' : 'Removed from menu.';
        $this->module->update($module->id, array('updated_by' => auth()->id(), 'show_in_menu' => $show_in_menu));
        $updated = $this->module->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
