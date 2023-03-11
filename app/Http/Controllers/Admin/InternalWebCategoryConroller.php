<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InternalWebCategoryRequest;
use App\Repositories\InternalWebCategoryRepository;
use Illuminate\Http\Request;

class InternalWebCategoryConroller extends Controller
{
    protected $internalWebCategory;

    public $title = 'Internal Web Category';

    public function __construct(InternalWebCategoryRepository $internalWebCategory)
    {
        $this->InternalWebCategory = $internalWebCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    protected function multiParent($parent_id, $language_id)
    {
        if ($parent_id != '') {
            if ($category = $this->InternalWebCategory->where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
                return $category->id;
            }
        }

        return $parent_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['download-category', 'view']);
        $title = $this->title;
        $internalWebCategories = $this->InternalWebCategory->where('language_id', $this->preferredLanguage)->orderBy('display_order')->get();

        return view('admin.internalWebCategory.index', compact('title'))
            ->withInternalWebCategories($internalWebCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['download-category', 'add']);
        $title = 'Add Internal Web Category';
        $parents = $this->InternalWebCategory->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.internalWebCategory.create', compact('title'))
            ->withParents($parents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InternalWebCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['download-category', 'add']);
        $data = $request->except(['_token']);
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_insert = $this->InternalWebCategory->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['parent_id'] = $this->multiParent($data['parent_id'], $language_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        if ($lang_items[$count]['existing_record_id'] != NULL && !empty($lang_items[$count]['parent_id'])) {
                            $category = $this->InternalWebCategory->where('existing_record_id', $data['parent_id'])->first();
                            $lang_items[$count]['parent_id'] = isset($category) ? $category->id : $lang_items['parent_id'];
                        }
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->InternalWebCategory->model()->insert($lang_items);
            }

            return redirect()->route('admin.internal-web-category.index')
                ->with('flash_notice', 'Internal Web Category Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Internal Web Category can not be created.');
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
        $this->authorize('master-policy.perform', ['download-category', 'edit']);
        $title = 'Edit Internal Web Cateogry';
        $internalWebCategory = $this->InternalWebCategory->find($id);
        $lang_content = $this->InternalWebCategory->where('existing_record_id', $internalWebCategory->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $parent = $this->InternalWebCategory->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $parent->where('parent_id', '!=', $id)->all();

        return view('admin.internalWebCategory.edit', compact('title'))
            ->withInternalWebCategory($internalWebCategory)
            ->withLangContent($lang_content)
            ->withParents($parents)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InternalWebCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['download-category', 'edit']);
        $content = $this->InternalWebCategory->find($id);
        $data = $request->except(['_token', '_method']);
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['show_upload_file'] = isset($request['show_upload_file']) ? 1 : 0;
        $data['edit'] = isset($request['edit']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->InternalWebCategory->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['parent_id'] = $this->multiParent($data['parent_id'], $language_id);
                    $lang_items['display_order'] = $existing_record_id->display_order;
                    unset($lang_items['post']);
                    unset($lang_items['post']);
                    $this->InternalWebCategory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.internal-web-category.index')
                ->with('flash_notice', 'Internal Web Category updated successfully');
        } else {

            return redirect()->back()->withInput()
                ->with('flash_notice', 'Internal Web Category can not be updated.');
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
        $this->authorize('master-policy.perform', ['download-category', 'delete']);
        $this->InternalWebCategory->where('existing_record_id', $id)->delete();
        $content = $this->InternalWebCategory->find($id);
        if ($this->InternalWebCategory->destroy($content->id)) {
            $message = 'Internal Web Category deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['download-category', 'changeStatus']);
        $content = $this->InternalWebCategory->find($request->get('id'));
        $status = $content->is_active == 0 ? 1 : 0;
        $message = $content->is_active == 0 ? 'Internal web category is published.' : 'Internal web category is unpublished.';
        $this->InternalWebCategory->changeStatus($content->id, $status);
        $this->InternalWebCategory->update($content->id, array('updated_by' => auth()->id()));
        $updated = $this->InternalWebCategory->find($request->get('id'));
        if ($multiContenct = $this->InternalWebCategory->where('existing_record_id', $content->id)->first()) {
            $this->InternalWebCategory->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->InternalWebCategory->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->InternalWebCategory->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->InternalWebCategory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->InternalWebCategory->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
