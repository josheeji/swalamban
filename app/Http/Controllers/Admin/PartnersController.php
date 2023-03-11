<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Partners\PartnerStoreRequest;
use App\Http\Requests\Admin\Partners\PartnerUpdateRequest;
use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PartnersController extends Controller
{

    protected $post, $postCategory, $preferredLanguage;
    public $title = 'Partner';

    public function __construct(PostRepository $post, PostCategoryRepository $postCategory)
    {
        auth()->shouldUse('admin');
        $this->post = $post;
        $this->postCategory = $postCategory;
        $this->preferredLanguage = session('site_settings')['preferred_language'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $partners = $this->post->where('type', ConstantHelper::POST_TYPE_PARTNER)->where('language_id', $this->preferredLanguage)->orderby('display_order', 'asc')->get();
        return view('admin.partner.index', compact('title', 'partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['partner', 'add']);
        $title = $this->title;
        return view('admin.partner.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['partner', 'add']);
        $data = $request->except(['banner', 'image']);
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'partners', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'partners', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['type'] = ConstantHelper::POST_TYPE_PARTNER;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['show_image'] = isset($data['show_image']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
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
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : '';
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->post->model()->insert($lang_items);
            }
            return redirect()->route('admin.partners.index')
                ->with('flash_success', 'Partner Created Successfully.');
        }
        return redirect()->back()->withInput()->with('flash_error', 'Partner can not be added.');
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
        $this->authorize('master-policy.perform', ['partner', 'edit']);
        $partner = $this->post->find($id);
        $title = $this->title;
        $lang_content = $this->post->where('existing_record_id', $partner->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.partner.edit', compact('title', 'partner'))
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['partner', 'edit']);
        $post = $this->post->find($id);
        $data = $request->except(['banner', 'image', '_token', '_method']);
        $data['type'] = ConstantHelper::POST_TYPE_PARTNER;
        if ($request->hasFile('banner')) {
            MediaHelper::destroy($post->banner);
            $filelocation = MediaHelper::upload($request->file('banner'), 'partners', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            MediaHelper::destroy($post->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'partners', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['show_image'] = isset($request['show_image']) ? 1 : 0;
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
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];

                    $this->post->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.partners.index')
                ->with('flash_success', 'Partner updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_error', 'Partner can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['partner', 'delete']);
        $post = $this->post->find($id);
        if ($this->post->destroy($post->id)) {
            if (!empty($post->banner)) {
                MediaHelper::destroy($post->banner);
            }
            if (!empty($post->image)) {
                MediaHelper::destroy($post->image);
            }
            $message = 'Partner deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['partner', 'changeStatus']);
        $post = $this->post->find($request->get('id'));
        $status = $post->is_active == 0 ? 1 : 0;
        $message = $post->is_active == 0 ? 'Published' : 'Unpublished';
        $this->post->changeStatus($post->id, $status);
        $updated = $this->post->find($request->get('id'));
        if ($multiContenct = $this->post->where('existing_record_id', $post->id)->first()) {
            $this->post->changeStatus($multiContenct->id, $status);
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
        $content = $this->post->find($id);
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
                $other_posts = $this->post->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->post->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->post->update($record->id, ['banner' => $content->banner, 'image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}