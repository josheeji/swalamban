<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\GrievanceRepository;
use Illuminate\Http\Request;

class GrievanceController extends Controller
{
    protected $grievance;
    public $title = 'Grievance';

    public function __construct(GrievanceRepository $grievance)
    {
        auth()->shouldUse('admin');
        $this->grievance = $grievance;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['grievance', 'view']);
        $title = $this->title;
        $grievances = $this->grievance->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.grievance.index', compact('title', 'grievances'));
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
        $this->authorize('master-policy.perform', ['grievance', 'view']);
        $title = $this->title;
        $grievance = $this->grievance->find($id);

        return view('admin.grievance.show', compact('title', 'grievance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grievance = $this->grievance->find($id);

        return view('admin.grievance.edit', compact('grievance'));
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
        $this->authorize('master-policy.perform', ['grievance', 'delete']);
        $grievance = $this->grievance->find($id);
        if ($this->grievance->destroy($grievance->id)) {
            $message = 'Account type deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
