<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Repositories\GalleryVideoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GalleryVideoController extends Controller
{

    public $title = 'Video Link';

    protected $video;

    public function __construct(GalleryVideoRepository $video)
    {
        $this->video = $video;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? ConstantHelper::DEFAULT_LANGUAGE : SettingHelper::setting('preferred_language');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('master-policy.perform', ['video-links', 'view']), 403);
        $title = $this->title;
        $video = $this->video->orderBy('display_order', 'asc')->where('language_id', $this->preferredLanguage)->orderBy('created_at', 'desc')->get();

        return view('admin.video.index', compact('title', 'video'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('master-policy.perform', ['video-links', 'add']), 403);
        $title = $this->title;

        return view('admin.video.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        abort_if(Gate::denies('master-policy.perform', ['video-links', 'add']), 403);
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $data['created_by'] = Auth::user()->id;
        $preferred_language = $this->preferredLanguage;
        $request->validate([
            'title.' . $preferred_language => ['required', 'string'],
            'link'=>'required'
        ],
            ['title.' . $preferred_language . '.required' => 'The title field is required.',]);
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_insert = $this->video->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['link'] = $preferred_insert->link;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->video->model()->insert($lang_items);
            }
        }

        return redirect()->route('admin.gallery-video.index')
            ->with('flash_notice', 'Video Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('master-policy.perform', ['video-links', 'edit']), 403);
        $title = $this->title;
        $video = $this->video->find($id);
        $lang_content = $this->video->where('existing_record_id', $video->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.video.edit', compact('title', 'video'))->withLangContent($lang_content)->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['video-links', 'edit']), 403);
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $data['updated_by'] = Auth::user()->id;
        $preferred_language = $this->preferredLanguage;
        $request->validate([
            'title.' . $preferred_language => ['required', 'string'],
            'link'=>'required'
        ],
            ['title.' . $preferred_language . '.required' => 'The title field is required.',]);
        $existing_record_id = $this->video->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $this->video->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
        }

        return redirect()->route('admin.gallery-video.index')
            ->with('flash_notice', 'Video updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required|exists:gallery_videos,id',
        ]);
        $video = $this->video->find($request->get('id'));
        $this->video->update($video->id, array('deleted_by' => Auth::user()->id));
        $this->video->destroy($video->id);
        $message = 'Video link deleted successfully.';

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['video-links', 'changeStatus']), 403);
        $video = $this->video->find($request->get('id'));
        if ($video->is_active == 0) {
            $status = 1;
            $message = 'Video with title "' . $video->title . '" is published.';
        } else {
            $status = 0;
            $message = 'Video with title "' . $video->title . '" is unpublished.';
        }
        $this->video->changeStatus($video->id, $status);
        $this->video->update($video->id, array('updated_by' => Auth::user()->id));
        $updated = $this->video->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->video->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
