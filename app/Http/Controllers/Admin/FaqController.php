<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\FaqStoreRequest;
use App\Repositories\FaqCategoryRepository;
use App\Repositories\FaqRepository;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    protected $faq, $preferredLanguage;
    protected $category;

    public $title = 'FAQ';

    public function __construct(FaqRepository $faq, FaqCategoryRepository $category)
    {
        $this->faq = $faq;
        $this->category = $category;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
    }

    protected function faqMultiCategory($category_id, $language_id)
    {
        if ($category = $this->category->where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category_id)
    {
        $this->authorize('master-policy.perform', ['faq', 'view']);
        $title = $this->title;
        $category = $this->category->find($category_id);
        $faq = $this->faq->where('faq_category_id', $category_id)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.faqList.index', compact('title'))
            ->withCategory($category)
            ->withFaq($faq);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($category_id = null)
    {
        $this->authorize('master-policy.perform', ['faq', 'add']);
        $title = $this->title;
        return view('admin.faqList.create', compact('title'))
            ->withCategory($this->category->find($category_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqStoreRequest $request, $category_id)
    {
        $this->authorize('master-policy.perform', ['faq', 'add']);
        $data = $request->except(['_token']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['faq_category_id'] = $category_id;
        $preferred_language_item['question'] = $data['question'][$preferred_language];
        $preferred_language_item['answer'] = $data['answer'][$preferred_language];

        $preferred_insert = $this->faq->create($preferred_language_item);

        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;

            unset($data['_token']);
            foreach ($data['question'] as $language_id => $value) {

                if ($language_id != $preferred_language) {
                    if ($data['question'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['faq_category_id'] = $this->faqMultiCategory($category_id, $language_id);
                        $lang_items[$count]['question'] = $data['question'][$language_id];
                        $lang_items[$count]['answer'] = $data['answer'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->faq->model()->insert($lang_items);
            }
            return redirect()->route('admin.faq.index', $category_id)
                ->with('flash_notice', 'FAQ Added Successfully.');
        } else {
            return redirect()->back()->with('flash_error', 'Something went wrong during the operation.');
        }
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
    public function edit($category_id, $id)
    {
        $this->authorize('master-policy.perform', ['faq', 'edit']);
        $data['title'] = $this->title;
        $data['category'] = $this->category->find($category_id);
        $data['category_id'] = $category_id;
        $data['faq'] = $this->faq->find($id);
        $data['categories'] = $this->category->orderBy('title', 'asc')->get();

        $lang_content = $this->faq->where('existing_record_id', $id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $data['langContent'] = $lang_content->groupBy('language_id');
        $data['preferredLanguage'] = $this->preferredLanguage;

        return view('admin.faqList.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_id, $id)
    {
        $this->authorize('master-policy.perform', ['faq', 'edit']);
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->faq->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['question'] as $language_id => $value) {
                if ($data['question'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['faq_category_id'] = $this->faqMultiCategory($category_id, $language_id);
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['question'] = $data['question'][$language_id];
                    $lang_items['answer'] = $data['answer'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    $this->faq->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.faq.index', $category_id)
                ->with('flash_notice', 'FAQ updated successfully');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'FAQ can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['faq', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:faqs,id',
        ]);
        $faq = $this->faq->find($request->get('id'));
        $this->faq->destroy($faq->id);
        $message = 'FAQ deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['faq', 'changeStatus']);
        $faq = $this->faq->find($request->get('id'));
        $status = $faq->is_active == 0 ? 1 : 0;
        $message = $faq->is_active == 0 ? 'FAQ with question "' . $faq->question . '" is published.' : 'FAQ with question "' . $faq->question . '" is unpublished.';

        $this->faq->changeStatus($faq->id, $status);
        $updated = $this->faq->find($request->get('id'));
        if ($multiContent = $this->faq->where('existing_record_id', $faq->id)->first()) {
            $this->faq->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->faq->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->faq->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->faq->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->faq->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}