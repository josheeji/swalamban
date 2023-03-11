<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MemberSaveRequest;
use App\Models\Members;
use App\Repositories\MembersRepository;
use App\Repositories\MemberTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class MembersController extends Controller
{
    protected $members, $memberType;

    public function __construct(MembersRepository $members, MemberTypeRepository $memberType)
    {
        $this->members = $members;
        $this->memberType = $memberType;
        auth()->shouldUse('admin');
    }

    public function index()
    {

        $this->authorize('master-policy.perform', ['members', 'view']);
      $members = $this->members->orderBy('created_at', 'desc')->paginate('10');;
        return view('admin.member.index')->withMembers($members);
    }

    public function create()
    {
        $this->authorize('master-policy.perform', ['members', 'add']);
        $membertypes = $this->memberType->get();

        return view('admin.member.add')->withMembertypes($membertypes);
    }

    public function store(MemberSaveRequest $request)
    {
        $this->authorize('master-policy.perform', ['members', 'add']);
        $data = $request->except(['image']);
        if ($request->get('image')) {
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64', '', $image);
            $image = str_replace('', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'members/' . $saveName . 'png';
            Storage::put($data['image'], $imageData);

        }
        $data['is_active'] = isset($request->is_active) ? 1 : 0;
        if ($this->members->create($data)) {
            return redirect()->route('admin.members.index')
                ->with('flash_notice', 'Members Created Successfully.');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'Members can not be Create.');
    }

    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['members', 'edit']);
        $membertypes = $this->memberType->get();
        $member = $this->members->find($id);
        return view('admin.member.edit')->withMembertypes($membertypes)->withMember($member);

    }

    public function update(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['members', 'add']);
        $member = $this->members->find($id);
        $data = $request->except(['image', '_token', '_method']);
        if ($request->get('image')) {
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64', '', $image);
            $image = str_replace('', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'members/' . $saveName . 'png';
            Storage::put($data['image'], $imageData);

        }
        $data['is_active'] = isset($request->is_active) ? 1 : 0;
        if ($this->members->update($member->id, $data)) {
            return redirect()->route('admin.members.index')
                ->with('flash_notice', 'Members updated successfully');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'Members can not be Update.');
    }


    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['members', 'delete']);
        $member = $this->members->find($request->get('id'));
        if ($this->members->destroy($member->id)) {
            $message = 'Member deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {

        $this->authorize('master-policy.perform', ['members', 'changeStatus']);
        $member = $this->members->find($request->get('id'));
        if ($member->is_active == 0) {
            $status = 1;
            $message = 'Member with name "' . $member->name . '" is published.';
        } else {
            $status = 0;
            $message = 'Member with name "' . $member->name . '" is unpublished.';
        }

        $this->members->changeStatus($member->id, $status);
        $this->members->update($member->id, ['is_active'=> $status ]);
        $updated = $this->members->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
