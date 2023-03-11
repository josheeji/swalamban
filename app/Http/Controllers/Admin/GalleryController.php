<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Repositories\GalleryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gallery\GalleryStoreRequest;
use App\Http\Requests\Admin\Gallery\GalleryUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public $title = 'Gallery';

    protected $gallery, $preferredLanguage;

    public function __construct(GalleryRepository $gallery)
    {
        $this->gallery = $gallery;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? ConstantHelper::DEFAULT_LANGUAGE : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['gallery', 'view']);
        $title = $this->title;
        $galleries = $this->gallery->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate('10');

        return view('admin.gallery.index')->withGalleries($galleries)->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['gallery', 'add']);
        $title = $this->title;

        return view('admin.gallery.create')->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['gallery', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'gallery');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request->is_active) ? 1 : 0;
        $data['created_by'] = Auth::user()->id;
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        // $preferred_language_item['description'] = $data['description'][$preferred_language];
        $preferred_insert = $this->gallery->create($preferred_language_item);
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
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        // $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->gallery->model()->insert($lang_items);
            }

            return redirect()->route('admin.gallery.index')
                ->with('flash_notice', 'Gallery Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Gallery can not be Create.');
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
        $this->authorize('master-policy.perform', ['gallery', 'edit']);
        $title = $this->title;
        $gallery = $this->gallery->find($id);
        $lang_content = $this->gallery->where('existing_record_id', $gallery->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.gallery.edit')->withGallery($gallery)->withTitle($title)->withLangContent($lang_content)->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['gallery', 'edit']);
        $gallery = $this->gallery->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            if (!empty($gallery->image) && file_exists('storage/' . $gallery->image)) {
                MediaHelper::destroy($gallery->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'gallery');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['updated_by'] = Auth::user()->id;
        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->gallery->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    // $lang_items['description'] = $data['description'][$language_id];
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;

                    $this->gallery->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.gallery.index')
                ->with('flash_notice', 'Gallery updated successfully');
        };

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Gallery Can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['gallery', 'delete']);
        $gallery = $this->gallery->find($request->get('id'));
        if ($this->gallery->destroy($gallery->id)) {
            $message = 'Gallery deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['gallery', 'changeStatus']);
        $gallery = $this->gallery->find($request->get('id'));
        if ($gallery->is_active == 0) {
            $status = 1;
            $message = 'Gallery with title "' . $gallery->title . '" is published.';
        } else {
            $status = 0;
            $message = 'Gallery with title "' . $gallery->title . '" is unpublished.';
        }
        $this->gallery->changeStatus($gallery->id, $status);
        $this->gallery->update($gallery->id, array('updated_by' => Auth::user()->id));
        $updated = $this->gallery->find($request->get('id'));
        if ($multiContent = $this->gallery->where('existing_record_id', $gallery->id)->first()) {
            $this->gallery->changeStatus($multiContent->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->gallery->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->gallery->find($id);
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
                $preferred_language = $this->preferredLanguage;
                $other_posts = $this->gallery->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->gallery->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->gallery->update($record->id, ['image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
