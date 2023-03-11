<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\Admin\ContentRequest;
use App\Repositories\ContentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ContentBlockRepository;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    protected $content, $preferredLanguage;

    public function __construct(
        ContentRepository $content,
        ContentBlockRepository $contentBlock
    ) {
        $this->content = $content;
        $this->contentBlock = $contentBlock;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? ConstantHelper::DEFAULT_LANGUAGE : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['content', 'view']);
        $contents = $this->content->with('parent')->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.content.index', ['contents' => $contents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['content', 'add']);
        $parents = $this->content->with('allChild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        return view('admin.content.create', ['parents' => $parents]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ContentRequest $request)
    {
        $this->authorize('master-policy.perform', ['content', 'add']);
        $preferred_language = $this->preferredLanguage;
        $data = $request->except(['_token', 'image', 'banner', 'blocks']);
        $commonData = $request->except(['_token', 'multiData', 'image', 'banner', 'blocks']);
        $blocks = $request->get('blocks');
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'content');
            $commonData['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'content', true);
            $commonData['banner'] = $filelocation['storage'];
        }

        $commonData['is_show_member_link'] = $request->has('is_show_member_link') ? $request->post('is_show_member_link') : 0;

        $commonData['is_active'] = $request->has('is_active') ? $request->post('is_active') : 0;
        /**
         * Insert preferred language item first.
         */
        $preferred_language_item = $data['multiData'][$preferred_language];
        $preferred_language_item = array_merge($preferred_language_item, $commonData);
        if ($preferred_insert = $this->content->create($preferred_language_item)) {
            foreach ($data['multiData'] as $language_id => $multiData) {
                $lang_items = [];
                if ($language_id != $preferred_language && $multiData['title'] != NULL) {
                    $lang_items = $multiData;
                    $lang_items['existing_record_id'] = $preferred_insert->id;
                    $lang_items['language_id'] = $language_id;
                    $lang_items = array_merge($lang_items, $commonData);
                    $this->content->create($lang_items);
                }
            }

            if (isset($blocks) && !empty($blocks)) {
                $this->saveBlock($preferred_insert, $blocks, $request);
            }
            return redirect()->route('admin.contents.index')->with('flash_success', 'Content created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Content can not be created.');
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['content', 'edit']);
        $content = $this->content->find($id);
        $lang_content = $this->content->where('existing_record_id', $content->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $patenr = $this->content->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->where('parent_id', '!=', $id)->all();

        $blocks = $this->contentBlock->where('content_id', $content->id)->where('language_id', $this->preferredLanguage)->get();
        return view('admin.content.edit', ['content' => $content, 'langContent' => $lang_content, 'blocks' => $blocks, 'parents' => $parents, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ContentRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['content', 'edit']);
        $content = $this->content->find($id);
        $preferred_language = $this->preferredLanguage;
        $data = $request->except(['_token', 'image', 'banner', 'blocks']);
        $commonData = $request->except(['_token', 'post', 'multiData', 'image', 'banner', 'blocks']);
        $blocks = $request->get('blocks');
        if ($request->hasFile('image')) {
            if (file_exists('storage/' . $content->image) && !empty($content->image)) {
                MediaHelper::destroy($content->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'content');
            $commonData['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            if (file_exists('storage/' . $content->banner) && !empty($content->banner)) {
                MediaHelper::destroy($content->banner);
            }
            $filelocation = MediaHelper::upload($request->file('banner'), 'content', true);
            $commonData['banner'] = $filelocation['storage'];
        }

        $commonData['is_show_member_link'] = $request->has('is_show_member_link') ? $request->post('is_show_member_link') : 0;
        $commonData['link_target'] = $request->has('link_target') ? $request->post('link_target') : 0;
        $commonData['is_active'] = $request->has('is_active') ? $request->post('is_active') : 0;
        $commonData['show_children'] = $request->has('show_children') ? $request->post('show_children') : 0;
        $commonData['show_image'] = $request->has('show_image') ? $request->post('show_image') : 0;
        if ($existing_record_id = $this->content->find($data['post'][$preferred_language]) ?? 0) {
            foreach ($data['multiData'] as $language_id => $multiData) {
                $lang_items = [];
                if ($data['multiData'][$language_id]['title'] != NULL) {
                    $lang_items = $multiData;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items = array_merge($lang_items, $commonData);
                    $this->content->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            if (isset($blocks) && !empty($blocks)) {
                $this->saveBlock($existing_record_id, $blocks, $request);
            }
            return redirect()->route('admin.contents.index')->with('flash_success', 'Content updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Content can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['content', 'delete']);
        $content = $this->content->find($id);
        $this->content->update($id, ['title' => $content->title . '-' . rand(0, 9999)]);
        $this->content->where('existing_record_id', $id)->delete();
        if ($this->content->destroy($content->id)) {
            if (!empty($content->banner)) {
                MediaHelper::destroy($content->banner);
            }
            if (!empty($content->image)) {
                MediaHelper::destroy($content->image);
            }
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['content', 'changeStatus']);
        $content = $this->content->find($request->get('id'));
        $status = $content->is_active == 0 ? 1 : 0;
        $message = $content->is_active == 0 ? 'Content published.' : 'Content unpublished.';
        $this->content->changeStatus($content->id, $status);
        $this->content->update($content->id, array('updated_by' => auth()->id()));
        $updated = $this->content->find($request->get('id'));
        if ($multiContenct = $this->content->where('existing_record_id', $content->id)->first()) {
            $this->content->changeStatus($multiContenct->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->content->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->content->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->content->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->content->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->content->find($id);
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
                $other_posts = $this->content->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->content->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->content->update($record->id, ['banner' => $content->banner, 'image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function block(Request $request)
    {
        $index = $request->get('index');
        return view('admin.content.block', ['index' => $index]);
    }

    public function removeBlock(Request $request)
    {
        $id = $request->get('id');
        $block =  $this->contentBlock->find($id);
        if ($block->destroy($id)) {
            MediaHelper::destroy($block->image);
            if ($multiContent = $this->contentBlock->where('existing_record_id', $id)->get()) {
                foreach ($multiContent as $content) {
                    $content->destroy($content->id);
                }
            }
            return 'success';
        }
        return 'error';
    }

    public function removeBlockImage(Request $request)
    {
        $id = $request->get('id');
        if ($block =  $this->contentBlock->find($id)) {
            MediaHelper::destroy($block->image);
            $block->image = '';
            $block->save();
            if ($multiContent = $this->contentBlock->where('existing_record_id', $id)->get()) {
                foreach ($multiContent as $content) {
                    $content->image = '';
                    $content->save();
                }
            }
            return 'success';
        }
        return 'error';
    }

    public function saveBlock($content, $blocks, $request)
    {
        $pk = [];
        if (isset($blocks) && is_array($blocks)) {
            foreach ($blocks as $index => $block) {
                $image = '';
                if ($request->hasFile("blocks.{$index}.image")) {
                    $filelocation = MediaHelper::upload($request->file("blocks.{$index}.image"), 'content');
                    $image = $filelocation['storage'];
                }
                foreach ($block as $language => $blockData) {
                    $contentID = $content->id;
                    if ($content->language_id != $language) {
                        if ($multiContent = $this->content->where('existing_record_id', $content->id)->where('language_id', $language)->first()) {
                            $contentID = $multiContent->id;
                        }
                        if (isset($pk[$index])) {
                            $blockData['existing_record_id'] = $pk[$index];
                        } else {
                        }
                    }
                    $blockData['content_id'] = $contentID;
                    $blockData['language_id'] = $language;
                    if ($request->hasFile("blocks.{$index}.image")) {
                        $blockData['image'] = $image;
                    }
                    if (isset($blockData['id']) && !empty($blockData['id'])) {
                        $id = $blockData['id'];
                        unset($blockData['id']);
                        $this->contentBlock->model()->updateOrCreate(['id' => $id], $blockData);
                    } else {
                        $blockData['created_by'] = Auth::user()->id;
                        $model = $this->contentBlock->create($blockData);
                        $pk[$index] = $model->id;
                    }
                }
            }
        }
    }
}