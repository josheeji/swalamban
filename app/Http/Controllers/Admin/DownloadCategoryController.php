<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DownloadCategoryRequest;
use App\Repositories\DownloadCategoryRepository;
use Illuminate\Http\Request;

class DownloadCategoryController extends Controller
{
    protected $downloadCategory;

    public $title = 'Download Category';

    public function __construct(DownloadCategoryRepository $downloadCategory)
    {
        $this->downloadCategory = $downloadCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    protected function multiParent($parent_id, $language_id)
    {
        if ($parent_id != '') {
            if ($category = $this->downloadCategory->where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
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
        $downloadCategories = $this->downloadCategory->where('language_id', $this->preferredLanguage)->orderBy('display_order')->get();

        return view('admin.downloadCategory.index', compact('title'))
            ->withDownloadCategories($downloadCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['download-category', 'add']);
        $title = 'Add Download Category';
        $parents = $this->downloadCategory->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.downloadCategory.create', compact('title'))
            ->withParents($parents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DownloadCategoryRequest $request)
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
        $preferred_insert = $this->downloadCategory->create($preferred_language_item);
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
                            $category = $this->downloadCategory->where('existing_record_id', $data['parent_id'])->first();
                            $lang_items[$count]['parent_id'] = isset($category) ? $category->id : $lang_items['parent_id'];
                        }
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->downloadCategory->model()->insert($lang_items);
            }

            return redirect()->route('admin.download-category.index')
                ->with('flash_notice', 'Download Category Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Download Category can not be created.');
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
        $title = 'Edit Download Cateogry';
        $downloadCategory = $this->downloadCategory->find($id);
        $lang_content = $this->downloadCategory->where('existing_record_id', $downloadCategory->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $patenr = $this->downloadCategory->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->where('parent_id', '!=', $id)->all();

        return view('admin.downloadCategory.edit', compact('title'))
            ->withDownloadCategory($downloadCategory)
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
    public function update(DownloadCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['download-category', 'edit']);
        $content = $this->downloadCategory->find($id);
        $data = $request->except(['_token', '_method']);
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['edit'] = isset($request['edit']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->downloadCategory->find($data['post'][$preferred_language]) ?? 0;
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
                    $this->downloadCategory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.download-category.index')
                ->with('flash_notice', 'Download Category updated successfully');
        } else {

            return redirect()->back()->withInput()
                ->with('flash_notice', 'Download Category can not be updated.');
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
        $this->downloadCategory->where('existing_record_id', $id)->delete();
        $content = $this->downloadCategory->find($id);
        if ($this->downloadCategory->destroy($content->id)) {
            $message = 'Download Category deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['download-category', 'changeStatus']);
        $content = $this->downloadCategory->find($request->get('id'));
        $status = $content->is_active == 0 ? 1 : 0;
        $message = $content->is_active == 0 ? 'Download category is published.' : 'Download category is unpublished.';
        $this->downloadCategory->changeStatus($content->id, $status);
        $this->downloadCategory->update($content->id, array('updated_by' => auth()->id()));
        $updated = $this->downloadCategory->find($request->get('id'));
        if ($multiContenct = $this->downloadCategory->where('existing_record_id', $content->id)->first()) {
            $this->downloadCategory->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->downloadCategory->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->downloadCategory->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->downloadCategory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->downloadCategory->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
