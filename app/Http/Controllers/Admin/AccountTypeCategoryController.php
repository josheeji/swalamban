<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountType\AccountTypeStoreRequest;
use App\Http\Requests\Admin\AccountTypeCategory\AccountTypeCategoryStoreRequest;
use App\Http\Requests\Admin\AccountTypeCategory\AccountTypeCategoryUpdateRequest;
use App\Http\Requests\Admin\AccountTypeCategory\AccountTypeUpdateCategoryRequest;
use App\Models\AccountTypeCategory;
use App\Repositories\AccountTypeCategoryRepository;
use App\Repositories\AccountTypeRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\ProductEnquiryRepository;
use Illuminate\Http\Request;

class AccountTypeCategoryController extends Controller
{
    protected $accountType, $preferredLanguage, $download, $accountTypeCategory;

    public function __construct(AccountTypeRepository $accountType, AccountTypeCategoryRepository $accountTypeCategory, DownloadRepository $download, ProductEnquiryRepository $productEnquiry)
    {
        $this->accountType = $accountType;
        $this->accountTypeCategory = $accountTypeCategory;
        $this->download = $download;
        $this->productEnquiry = $productEnquiry;
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
        $this->authorize('master-policy.perform', ['account-type-category', 'view']);
        $account_types = $this->accountTypeCategory->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.accountTypeCategory.index', ['account_types' => $account_types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'add']);
        return view('admin.accountTypeCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTypeCategoryStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'add']);
        $data = $request->except(['image', 'banner']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'account_type_category', true);
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'account_type_category', true);
            $data['banner'] = $filelocation['storage'];
        }
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        //        $preferred_language_item['feature'] = $data['feature'][$preferred_language];
        //        $preferred_language_item['description'] = $data['description'][$preferred_language];
        //        $preferred_language_item['terms_and_conditions'] = $data['terms_and_conditions'][$preferred_language];
        //        $preferred_language_item['faq'] = $data['faq'][$preferred_language];
        //        $preferred_language_item['interest_rate'] = $data['interest_rate'][$preferred_language];
        //        $preferred_language_item['minimum_balance'] = $data['minimum_balance'][$preferred_language];
        //        $preferred_language_item['interest_payment'] = $data['interest_payment'][$preferred_language];
        $preferred_insert = $this->accountTypeCategory->create($preferred_language_item);
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
                        $lang_items[$count]['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : '';
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        //                        $lang_items[$count]['feature'] = $data['feature'][$language_id];
                        //                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        //                        $lang_items[$count]['terms_and_conditions'] = $data['terms_and_conditions'][$language_id];
                        //                        $lang_items[$count]['faq'] = $data['faq'][$language_id];
                        //                        $lang_items[$count]['interest_rate'] = $data['interest_rate'][$language_id];
                        //                        $lang_items[$count]['minimum_balance'] = $data['minimum_balance'][$language_id];
                        //                        $lang_items[$count]['interest_payment'] = $data['interest_payment'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        //                        $lang_items[$count]['visible_in'] = $preferred_insert->visible_in;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->accountTypeCategory->model()->insert($lang_items);
            }
            return redirect()->route('admin.account-type-category.index')->with('flash_success', 'Product Category created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Product Category can not be created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountTypeCategory  $accountTypeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTypeCategory $accountTypeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountTypeCategory  $accountTypeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'edit']);
        $account_type_category = $this->accountTypeCategory->find($id);
        $lang_content = $this->accountTypeCategory->where('existing_record_id', $account_type_category->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        return view('admin.accountTypeCategory.edit', ['account_type' => $account_type_category, 'langContent' => $lang_content, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountTypeCategory  $accountTypeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(AccountTypeUpdateCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'edit']);
        $account_type_category = $this->accountTypeCategory->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            if (file_exists('storage/thumbs/' . $account_type_category->image) && $account_type_category->image != '') {
                MediaHelper::destroy($account_type_category->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'account_type_category');
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            MediaHelper::destroy($account_type_category->banner);
            $filelocation = MediaHelper::upload($request->file('banner'), 'account_type_category', true);
            $data['banner'] = $filelocation['storage'];
        }
        //        $data['visible_in'] = isset($data['visible_in']) ? $data['visible_in'] : null;
        //        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : 0;
        // $data['show_image'] = isset($data['show_image']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->accountTypeCategory->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    //                    $lang_items['interest_rate'] = $data['interest_rate'][$language_id];
                    //                    $lang_items['minimum_balance'] = $data['minimum_balance'][$language_id];
                    //                    $lang_items['interest_payment'] = $data['interest_payment'][$language_id];
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    //                    $lang_items['feature'] = $data['feature'][$language_id];
                    //                    $lang_items['description'] = $data['description'][$language_id];
                    //                    $lang_items['terms_and_conditions'] = $data['terms_and_conditions'][$language_id];
                    //                    $lang_items['faq'] = $data['faq'][$language_id];
                    // $lang_items['type'] = $data['type'];
                    $this->accountTypeCategory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.account-type-category.index')->with('flash_notice', 'Product Category updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Product Category can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountTypeCategory  $accountTypeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'delete']);
        $this->accountTypeCategory->where('existing_record_id', $id)->delete();
        $data = $this->accountTypeCategory->find($id);
        if ($this->accountTypeCategory->destroy($date->id)) {
            // if (!empty($accountType->banner)) {
            //     MediaHelper::destroy($accountType->banner);
            // }
            if (!empty($data->image)) {
                MediaHelper::destroy($data->image);
            }
            $message = 'Product Category deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['account-type-category', 'changeStatus']);
        $accountType = $this->accountTypeCategory->find($request->get('id'));
        $status = $accountType->is_active == 0 ? 1 : 0;
        $message = $accountType->is_active == 0 ? 'Product Category is published.' : 'Product Category is unpublished.';
        $this->accountTypeCategory->changeStatus($accountType->id, $status);
        $this->accountTypeCategory->update($accountType->id, array('updated_by' => auth()->id()));
        $updated = $this->accountTypeCategory->find($request->get('id'));
        if ($multiContent = $this->accountTypeCategory->where('existing_record_id', $accountType->id)->first()) {
            $this->accountTypeCategory->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->accountTypeCategory->find($id);
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
                $other_posts = $this->accountTypeCategory->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->accountTypeCategory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->accountTypeCategory->update($record->id, ['banner' => $content->banner, 'image' => $content->image]);
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