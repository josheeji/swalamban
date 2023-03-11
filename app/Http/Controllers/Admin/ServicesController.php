<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Services\ServicesStoreRequest;
use App\Http\Requests\Admin\Services\ServicesUpdateRequest;
use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $post, $postCategory, $preferredLanguage;

    public function __construct(PostRepository $post, PostCategoryRepository $postCategory)
    {
        auth()->shouldUse('admin');
        $this->post = $post;
        $this->postCategory = $postCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper
            ::setting('preferred_language');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['service', 'view']);
        $services = $this->post->where('type', ConstantHelper::POST_TYPE_SERVICE)->where('language_id', $this->preferredLanguage)->orderby('display_order', 'asc')->get();
        return view('admin.service.index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['service', 'add']);
        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicesStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['service', 'add']);
        $data = $request->except(['banner', 'image']);
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'services', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'services', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['type'] = ConstantHelper::POST_TYPE_SERVICE;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['layout'] = $data['layout'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];

        $preferred_insert = $this->post->create($preferred_language_item);

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
                        $lang_items[$count]['layout'] = $data['layout'][$language_id];
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : '';
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        $lang_items[$count]['visible_in'] = $preferred_insert->visible_in;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->post->model()->insert($lang_items);
            }
            return redirect()->route('admin.services.index')->with('flash_success', 'Service created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Service can not be added.');
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
        $this->authorize('master-policy.perform', ['service', 'edit']);
        $service = $this->post->find($id);
        $lang_content = $this->post->where('existing_record_id', $service->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.service.edit', ['service' => $service, 'langContent' => $lang_content, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServicesUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['service', 'edit']);
        $service = $this->post->find($id);
        $data = $request->except(['banner', 'image', '_token', '_method']);
        $data['type'] = ConstantHelper::POST_TYPE_SERVICE;
        if ($request->hasFile('banner')) {
            if (isset($service->banner) && file_exists('storage/' . $service->banner)) {
                MediaHelper::destroy($service->banner);
            }
            $filelocation = MediaHelper::upload($request->file('banner'), 'services', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            if (isset($service->image) && file_exists('storage/' . $service->images)) {
                MediaHelper::destroy($service->image);
            }

            $filelocation = MediaHelper::upload($request->file('image'), 'services', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['link_target'] = isset($data['link_target']) ? 1 : 0;
        $data['visible_in'] = isset($data['visible_in']) ? $data['visible_in'] : 0;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->post->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['layout'] = $data['layout'][$language_id];
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    $this->post->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.services.index')->with('flash_success', 'Service updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Service can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['service', 'delete']);
        $service = $this->post->find($id);
        if ($this->post->destroy($service->id)) {
            if (!empty($service->banner)) {
                MediaHelper::destroy($service->banner);
            }
            if (!empty($service->image)) {
                MediaHelper::destroy($service->image);
            }
            $message = 'Service deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['service', 'changeStatus']);
        $service = $this->post->find($request->get('id'));
        $status = $service->is_active == 0 ? 1 : 0;
        $message = $service->is_active == 0 ? 'Published' : 'Unpublished';
        $this->post->changeStatus($service->id, $status);
        $updated = $this->post->find($request->get('id'));
        if ($multiContent = $this->post->where('existing_record_id', $updated->id)->first()) {
            $this->post->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->post->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->post->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->post->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->post->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        if ($service = $this->post->find($id)) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($service->banner);
                    $service->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($service->image);
                    $service->image = null;
                    break;
            }
            $service->save();
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
