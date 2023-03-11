<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\MemberTypeRequest;
use App\Http\Requests\Admin\MemberTypeUpdateRequest;
use App\Repositories\MemberTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberTypeController extends Controller
{
    protected $memberType;

    public function __construct(MemberTypeRepository $memberType)
    {
        $this->memberType = $memberType;
        auth()->shouldUse('admin');
    }

    public function index(){

        $this->authorize('master-policy.perform', ['member-type', 'view']);
        $membersType = $this->memberType->orderBy('created_at', 'desc')->get();
        return view('admin.memberType.index')->withMembersType($membersType);
    }
    public function create(){
        $this->authorize('master-policy.perform', ['member-type', 'add']);
        return view('admin.memberType.add');
    }
    public function store(MemberTypeRequest $request){

        $this->authorize('master-policy.perform', ['member-type', 'add']);
        $data = $request->except('_token');
        if($this->memberType->create( $data)){
            return redirect()->route('admin.memberType.index')
                ->with('flash_notice', 'Member Created Successfully.');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'Member Type can not be created.');
    }
    public function edit($id){
        $this->authorize('master-policy.perform', ['member-type', 'edit']);
        $memberType= $this->memberType->find($id);
        return view('admin.memberType.edit')->withMemberType($memberType);
    }

    public function update(MemberTypeUpdateRequest $request,$id){
        $this->authorize('master-policy.perform', ['member-type', 'edit']);
        $memberType= $this->memberType->find($id);
        $data=$request->except(['_token','_method']);
        if($this->memberType->update($memberType->id,     $data)){
            return redirect()->route('admin.memberType.index')
                ->with('flash_notice', 'Member updated successfully');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'Member Type can not be Updated.');
    }
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform',['gallery','delete']);
        $memberType = $this->memberType->find($request->get('id'));
        if($this->memberType->destroy($memberType->id)){
            $message = 'Member TYpe deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

}
