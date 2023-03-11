<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FinancialReportCategoryRequest;
use App\Repositories\FinancialReportCategoryRepository;
use Illuminate\Http\Request;

class FinancialReportCategoryController extends Controller
{
    protected $category;

    public $title = 'Financial Report Category';

    public function __construct(FinancialReportCategoryRepository $category)
    {
        $this->category = $category;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    protected function multiParent($parent_id, $language_id)
    {
        if ($parent_id == null) {
            return $parent_id;
        }
        if ($parent = $this->category->where('existing_record_id', $parent_id)->where('language_id', $language_id)->first()) {
            return $parent->id;
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
        $this->authorize('master-policy.perform', ['financial-report-category', 'view']);

        $title = $this->title;
        $categories = $this->category->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order')
            ->get();

        return view('admin.financialReportCategory.index', compact('title'))
            ->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['financial-report-category', 'add']);

        $title = 'Add Download Category';
        $parents = $this->category->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.financialReportCategory.create', compact('title'))
            ->withParents($parents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialReportCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['financial-report-category', 'add']);
        $data = $request->except(['_token']);

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];

        $preferred_insert = $this->category->create($preferred_language_item);

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
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['parent_id'] = $this->multiParent($data['parent_id'], $language_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? null : $preferred_insert->display_order;
                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->category->model()->insert($lang_items);
            }
            return redirect()->route('admin.financial-report-category.index')
                ->with('flash_notice', 'Financial Report Category Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Financial Report Category can not be created.');
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
        $this->authorize('master-policy.perform', ['financial-report-category', 'edit']);
        $title = 'Edit Financial Report Cateogry';
        $category = $this->category->find($id);

        $lang_content = $this->category->where('existing_record_id', $category->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        $patenr = $this->category->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->where('parent_id', '!=', $id)->all();

        return view('admin.financialReportCategory.edit', compact('title'))
            ->withCategory($category)
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
    public function update(FinancialReportCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['financial-report-category', 'edit']);
        $content = $this->category->find($id);
        $data = $request->except(['_token', '_method']);
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['edit'] = isset($request['edit']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->category->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['display_order'] = $existing_record_id->display_order = null ? null : $existing_record_id->display_order;
                    unset($lang_items['post']);
                    $this->category->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.financial-report-category.index')
                ->with('flash_notice', 'Financial Report Category updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'Financial Report Category can not be updated.');
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
        $this->authorize('master-policy.perform', ['financial-report-category', 'delete']);
        $this->category->where('existing_record_id', $id)->delete();
        $content = $this->category->find($id);
        if ($this->category->destroy($content->id)) {
            $message = 'Financial Report Category deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['financial-report-category', 'changeStatus']);
        $content = $this->category->find($request->get('id'));
        $status = $content->is_active == 0 ? 1 : 0;
        $message = $content->is_active == 0 ? 'Financial Report category is published.' : 'Financial Report category is unpublished.';

        $this->category->changeStatus($content->id, $status);
        $this->category->update($content->id, array('updated_by' => auth()->id()));
        $updated = $this->category->find($request->get('id'));

        if ($multiContent = $this->category->where('existign_record_id', $content->id)->first()) {
            $this->category->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->category->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->category->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->category->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->category->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
