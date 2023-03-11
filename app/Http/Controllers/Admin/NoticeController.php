<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NoticeRequest;
use App\Repositories\NoticeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NoticeController extends Controller
{

    public $title = 'Notice';

    protected $notice;

    public function __construct(NoticeRepository $notice)
    {
        $this->notice = $notice;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['notice', 'view']);
        $title = $this->title;
        $notices = $this->notice->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.notice.index')->withNotices($notices)->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['notice', 'add']);
        $title = 'Add Notice';
        return view('admin.notice.create')->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticeRequest $request)
    {
        $this->authorize('master-policy.perform', ['notice', 'add']);
        $data = $request->except(['image']);
        if ($request->get('image')) {
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace('', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'notice/' . $saveName . 'png';
            Storage::put($data['image'], $imageData);
        }
        $data['slug'] = Str::slug($request->title);
        $data['is_active'] = isset($data['is_active'])  ? 1 : 0;
        if ($this->notice->create($data)) {
            return redirect()->route('admin.notice.index')->with('flash_notice', 'Notice Created Successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Notice Can not be Create.');
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
        $this->authorize('master-policy.perform', ['notice', 'edit']);
        $title = 'Edit Notice';
        $notice = $this->notice->find($id);
        return view('admin.notice.edit')->withNotice($notice)->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoticeRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['notice', 'edit']);
        $notice = $this->notice->find($id);
        $data = $request->except(['image']);
        if ($request->get('image')) {
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64', '', $image);
            $image = str_replace('', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'nottice/' . $saveName . 'png';
            Storage::put($data['image'], $imageData);
            if (Storage::exists($notice->image)) {
                Storage::delete($notice->image);
            }
        }
        $data['show_image'] = $request->has('show_image') ? 1 : 0;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['updated_by'] = Auth::user()->id;
        $data['slug'] = Str::slug($request->title);
        if ($this->notice->update($notice->id, $data)) {
            return redirect()->route('admin.notice.index')
                ->with('flash_notice', 'Notice updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Notice can not be Update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['notice', 'delete']);
        $notice = $this->notice->find($request->get('id'));
        $this->notice->where('existing_record_id', $id)->delete();
        if ($this->notice->destroy($notice->id)) {
            $message = 'Notice deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['notice', 'changeStatus']);
        $notice = $this->notice->find($request->get('id'));
        if ($notice->is_active == 0) {
            $status = 1;
            $message = 'Notice with title "' . $notice->title . '" is published.';
        } else {
            $status = 0;
            $message = 'Notice with title "' . $notice->title . '" is unpublished.';
        }

        $this->notice->changeStatus($notice->id, $status);
        $this->notice->update($notice->id, array('status_by' => Auth::user()->id));
        $updated = $this->notice->find($request->get('id'));
        if ($multiContent = $this->notice->where('existing_record_id', $notice->id)->first()) {
            $this->notice->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->notice->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
