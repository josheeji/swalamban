<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Repositories\FaqCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqCategory\FaqCategoryStoreRequest;
use App\Http\Requests\Admin\FaqCategory\FaqCategoryUpdateRequest;
use App\Repositories\FaqRepository;
use Illuminate\Support\Facades\Gate;

class FaqCategoryController extends Controller
{

    public $title = 'FAQ';

    protected $type, $preferredLanguage, $faq;

    public function __construct(FaqCategoryRepository $type, FaqRepository $faq)
    {
        $this->type = $type;
        $this->faq = $faq;
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
        abort_if(Gate::denies('master-policy.perform', ['faq', 'view']), 403);
        return view('admin.faqCategory.index')
            ->withType($this->type->where('language_id', $this->preferredLanguage)->get())
            ->withTitle($this->title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('master-policy.perform', ['faq', 'add']), 403);

        $title = $this->title;
        return view('admin.faqCategory.create')
            ->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqCategoryStoreRequest $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['faq', 'add']), 403);
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];

        $preferred_insert = $this->type->create($preferred_language_item);

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
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 999 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->type->model()->insert($lang_items);
            }
            return redirect()->route('admin.faq-category.index')
                ->with('flash_notice', 'FAQ Category Created Successfully.');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'FAQ category can not be created.');
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
        abort_if(Gate::denies('master-policy.perform', ['faq', 'edit']), 403);

        $title = $this->title;

        $type = $this->type->find($id);
        $lang_content = $this->type->where('existing_record_id', $type->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.faqCategory.edit')
            ->withType($type)
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage)
            ->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqCategoryUpdateRequest $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['faq', 'edit']), 403);
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $type = $this->type->update($id, $data);
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->type->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $type->slug;
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 999 : $existing_record_id->display_order;

                    $this->type->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.faq-category.index')
                ->with('flash_notice', 'FAQ Category updated successfully');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'FAQ Category can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['faq', 'delete']), 403);

        $this->validate($request, [
            'id' => 'required|exists:faq_categories,id',
        ]);
        $type = $this->type->find($request->get('id'));
        $this->faq->where('faq_category_id', $id)->delete();
        $this->type->destroy($type->id);
        $message = 'FAQ Category deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['faq', 'changeStatus']), 403);
        $type = $this->type->find($request->get('id'));
        $status = $type->is_active == 0 ? 1 : 0;
        $message = $type->is_active == 0 ? 'Category with title "' . $type->title . '" is published.' : 'Category with title "' . $type->title . '" is unpublished.';
        $this->type->changeStatus($type->id, $status);
        $updated = $this->type->find($request->get('id'));
        if ($multiContent = $this->type->where('existing_record_id', $type->id)->first()) {
            $this->type->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->type->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->type->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->type->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->type->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}