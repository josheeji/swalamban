<?php

namespace App\Http\Controllers\Admin;


use App\Helper\MediaHelper;
use App\Repositories\PopupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Popup\PopupStoreRequest;
use App\Http\Requests\Admin\Popup\PopupUpdateRequest;
use Validator;

class PopupController extends Controller
{
    protected $popup;

    public function __construct(PopupRepository $popup)
    {
        $this->popup = $popup;
        auth()->shouldUse('admin');
    }
    public function index()
    {
        $this->authorize('master-policy.perform', ['popup', 'view']);
        $popups = $this->popup->orderBy('created_at', 'desc')->get();
        return view('admin.popup.index', ['popups' => $popups]);
    }

    public function create()
    {
        $this->authorize('master-policy.perform', ['popup', 'add']);
        return view('admin.popup.create');
    }

    public function store(PopupStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['popup', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $fileName = rand(0, 9999) . '-' . $request->file('image')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('image'), 'popup',$fileName);
            $data['image'] = $filelocation;
        }
        $data['show_title'] = isset($request['show_title']) ? 1 : 0;
        $data['show_image'] = isset($request['show_image']) ? 1 : 0;
        $data['target'] = isset($request['target']) ? 1 : 0;
        $data['show_button'] = isset($request['show_button']) ? 1 : 0;
        $data['show_in_notification'] = isset($request['show_in_notification']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if ($this->popup->create($data)) {
            return redirect()->route('admin.popup.index')->with('flash_success', 'Popup created successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Popup is can not be create');
    }

    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['popup', 'edit']);
        $popup = $this->popup->findOrfail($id);

        return view('admin.popup.edit')->withPopup($popup);
    }

    public function update(PopupUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['popup', 'edit']);
        $data = $request->except(['image', '_token', '_method']);
        $popup = $this->popup->find($id);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($popup->image);
            $fileName = rand(0, 9999) . '-' . $request->file('image')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('image'), 'popup',$fileName);
            $data['image'] = $filelocation;
        }
        $data['show_title'] = isset($request['show_title']) ? 1 : 0;
        $data['show_image'] = isset($request['show_image']) ? 1 : 0;
        $data['target'] = isset($request['target']) ? 1 : 0;
        $data['show_button'] = isset($request['show_button']) ? 1 : 0;
        $data['show_in_notification'] = isset($request['show_in_notification']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['visible_in'] = (isset($data['visible_in']) && is_array($data['visible_in'])) ? implode(',', $data['visible_in']) : '';
        if ($this->popup->update($popup->id, $data)) {

            return redirect()->route('admin.popup.index')->with('flash_notice', 'Popup is Update Successfully');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Popup can not be Update');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['popup', 'delete']);
        $popup = $this->popup->findOrfail($id);
        if ($this->popup->destroy($popup->id)) {
            $message = 'Popup deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['popup', 'changeStatus']);
        $popup = $this->popup->find($request->get('id'));
        $status = $popup->is_active == 0 ? 1 : 0;
        $message = $popup->is_active == 0 ? 'Popup published.' : 'Popup unpublished.';
        $this->popup->update($popup->id, ['is_active' => $status]);
        $updated = $this->popup->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->popup->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $message = 'Oops! something went wrong.';
        $content = $this->popup->find($id);
        if ($content) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($content->banner);
                    $content->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($content->image);
                    $content->image = null;
                    break;
            }
            if ($content->save()) {
                $message = 'Content deleted successfully.';
            }
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
