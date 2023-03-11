<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\RemittanceAllianceRequestRepository;
use Illuminate\Http\Request;

class RemittanceAllianceRequestController extends Controller
{

    public $title = 'Remittance Alliance Request';
    protected $remittanceRequest;

    public function __construct(RemittanceAllianceRequestRepository $remittanceRequest)
    {
        $this->remittanceRequest = $remittanceRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['remittance-alliance-request', 'view']);
        $title = $this->title;
        $data = $this->remittanceRequest->where('type', 1)->orderBy('created_at', 'desc')->get();

        return view('admin.remittanceAlliance.request-index', compact('title'))
            ->withData($data);
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
        $this->authorize('master-policy.perform', ['remittance-alliance-request', 'view']);
        $title = $this->title;
        $data = $this->remittanceRequest->where('id', $id)->first();
        if (!$data) {
            abort('404');
        }
        return view('admin.remittanceAlliance.request-show', compact('title'))
            ->withData($data);
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
        $this->authorize('master-policy.perform', ['remittance-alliance-contact', 'delete']);
        $post = $this->remittanceRequest->find($id);
        if ($this->remittanceRequest->destroy($post->id)) {
            $message = 'Deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
