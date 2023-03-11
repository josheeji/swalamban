<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BannerStoreRequest;
use App\Http\Requests\Admin\BannerUpdateRequest;
use App\Repositories\BannerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;

class BannerController extends Controller
{
    protected $banner, $preferredLanguage;

    public function __construct(
        BannerRepository $banner
    ) {
        $this->banner = $banner;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['banner', 'view']);
        $banners = $this->banner->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.banner.index', ['banners' => $banners]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['banner', 'add']);
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['banner', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'banner', true, true);
            $data['image'] = $filelocation['storage'];
        }
        //        $data['visible_in'] = isset($data['visible_in']) ? $data['visible_in'] : null;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['caption'] = $data['caption'][$preferred_language];
        $preferred_language_item['description'] = "description";
        $preferred_language_item['link_text'] = $data['link_text'][$preferred_language];
        $preferred_language_item['display_order'] = 0;
        $preferred_insert = $this->banner->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL && !empty($value)) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $preferred_insert->image;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['caption'] = $data['caption'][$language_id];
                        $lang_items[$count]['link_text'] = $data['link_text'][$language_id];
                        $lang_items[$count]['description'] = "description";
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->banner->model()->insert($lang_items);
            }
            return redirect()->route('admin.banner.index')->with('flash_success', 'Banner created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Banner can not be created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['banner', 'edit']);
        $banner = $this->banner->find($id);
        $lang_content = $this->banner->where('existing_record_id', $banner->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        return view('admin.banner.edit', ['banner' => $banner, 'langContent' => $lang_content, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['banner', 'edit']);
        $banner = $this->banner->find($id);
        $data = $request->except(['image', '_token', '_method']);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($banner->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'banner', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['link_target'] = isset($data['link_target']) ? $data['link_target'] : null;
        //        $data['visible_in'] = isset($data['visible_in']) ? $data['visible_in'] : null;
        $data['show_block'] = isset($data['show_block']) ? $data['show_block'] : null;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['description'] = "description";
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->banner->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['description'] = "description";
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['caption'] = $data['caption'][$language_id];
                    $lang_items['link_text'] = $data['link_text'][$language_id];
                    $this->banner->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.banner.index')->with('flash_success', 'Banner updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Banner can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['banner', 'delete']);
        $banner = $this->banner->find($id);
        if ($this->banner->destroy($banner->id)) {
            MediaHelper::destroy($banner->image);
            $message = 'Banner deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['banner', 'changeStatus']);
        $banner = $this->banner->find($request->get('id'));
        $status = $banner->is_active == 0 ? 1 : 0;
        $message = $banner->is_active == 0 ? 'Banner published.' : 'Banner unpublished.';
        $this->banner->changeStatus($banner->id, $status);
        $updated = $this->banner->find($request->get('id'));

        if ($multiContenct = $this->banner->where('existing_record_id', $banner->id)->first()) {
            $this->banner->changeStatus($multiContenct->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->banner->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->banner->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->banner->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->banner->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        if ($banner = $this->banner->find($id)) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($banner->banner);
                    $banner->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($banner->image);
                    $banner->image = null;
                    break;
            }
            $banner->save();
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}