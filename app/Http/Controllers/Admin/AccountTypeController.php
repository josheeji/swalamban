<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Exports\LeadExport;
use App\Http\Requests\Admin\AccountType\AccountTypeStoreRequest;
use App\Http\Requests\Admin\AccountType\AccountTypeUpdateRequest;
use App\Repositories\AccountTypeCategoryRepository;
use Illuminate\Http\Request;
use App\Repositories\AccountTypeRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\ProductEnquiryRepository;

class AccountTypeController extends Controller
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
        $this->authorize('master-policy.perform', ['account-type', 'view']);
        $account_types = $this->accountType->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.accountType.index', ['account_types' => $account_types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['account-type', 'add']);
        $downloads = $this->download->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('title', 'asc')->get();
        $categories = $this->accountTypeCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('title', 'asc')->get();
        return view('admin.accountType.create', ['downloads' => $downloads, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTypeStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['content', 'add']);
        $data = $request->except(['image', 'banner']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'account_type', true);
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'account_type', true);
            $data['banner'] = $filelocation['storage'];
        }
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['show_image'] = $request->has('show_image') ? $request->post('show_image') : 0;
        $preferred_language_item['category_id'] = $request->category_id;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        //        $preferred_language_item['feature'] = $data['feature'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];
        //        $preferred_language_item['terms_and_conditions'] = $data['terms_and_conditions'][$preferred_language];
        //        $preferred_language_item['faq'] = $data['faq'][$preferred_language];
        //        $preferred_language_item['interest_rate'] = $data['interest_rate'][$preferred_language];
        //        $preferred_language_item['minimum_balance'] = $data['minimum_balance'][$preferred_language];
        //        $preferred_language_item['interest_payment'] = $data['interest_payment'][$preferred_language];
        $preferred_insert = $this->accountType->create($preferred_language_item);
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
                        $lang_items[$count]['category_id'] = $this->accountType->model()->multiCategory($preferred_insert->category_id, $language_id);
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : '';
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        $lang_items[$count]['show_image'] = $request->has('show_image') ? $request->post('show_image') : 0;
                        $lang_items[$count]['type'] = $preferred_insert->type;
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        //                        $lang_items[$count]['feature'] = $data['feature'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
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
                $this->accountType->model()->insert($lang_items);
            }
            return redirect()->route('admin.account-type.index')->with('flash_success', 'Account type created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Account type can not be created.');
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
        $this->authorize('master-policy.perform', ['account-type', 'edit']);
        $account_type = $this->accountType->find($id);
        $lang_content = $this->accountType->where('existing_record_id', $account_type->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $downloads = $this->download->where('is_active', 1)->where('language_id', $this->preferredLanguage)
            ->orderBy('title', 'asc')->get();
        $categories = $this->accountTypeCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('title', 'asc')->get();

        return view('admin.accountType.edit', ['account_type' => $account_type, 'categories' => $categories, 'langContent' => $lang_content, 'downloads' => $downloads, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountTypeUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['account-type', 'edit']);
        $account_type = $this->accountType->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            if (file_exists('storage/thumbs/' . $account_type->image) && $account_type->image != '') {
                MediaHelper::destroy($account_type->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'account_type');
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('banner')) {
            MediaHelper::destroy($account_type->banner);
            $filelocation = MediaHelper::upload($request->file('banner'), 'account_type', true);
            $data['banner'] = $filelocation['storage'];
        }
        //        $data['visible_in'] = isset($data['visible_in']) ? $data['visible_in'] : null;
        //        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : 0;
        $data['show_image'] = isset($data['show_image']) ? 1 : 0;
        $data['category_id'] = $data['category_id'];
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->accountType->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['category_id'] = $this->accountType->model()->multiCategory($data['category_id'], $language_id);

                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    //                    $lang_items['interest_rate'] = $data['interest_rate'][$language_id];
                    //                    $lang_items['minimum_balance'] = $data['minimum_balance'][$language_id];
                    //                    $lang_items['interest_payment'] = $data['interest_payment'][$language_id];
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    //                    $lang_items['feature'] = $data['feature'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    //                    $lang_items['terms_and_conditions'] = $data['terms_and_conditions'][$language_id];
                    //                    $lang_items['faq'] = $data['faq'][$language_id];
                    // $lang_items['type'] = $data['type'];
                    $this->accountType->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.account-type.index')->with('flash_notice', 'Account type updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Account type can not be updated.');
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
        $this->authorize('master-policy.perform', ['account-type', 'delete']);
        $this->accountType->where('existing_record_id', $id)->delete();
        $accountType = $this->accountType->find($id);
        if ($this->accountType->destroy($accountType->id)) {
            // if (!empty($accountType->banner)) {
            //     MediaHelper::destroy($accountType->banner);
            // }
            // if (!empty($accountType->image)) {
            //     MediaHelper::destroy($accountType->image);
            // }
            $message = 'Account type deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['account-type', 'changeStatus']);
        $accountType = $this->accountType->find($request->get('id'));
        $status = $accountType->is_active == 0 ? 1 : 0;
        $message = $accountType->is_active == 0 ? 'Account type is published.' : 'Account type is unpublished.';
        $this->accountType->changeStatus($accountType->id, $status);
        $this->accountType->update($accountType->id, array('updated_by' => auth()->id()));
        $updated = $this->accountType->find($request->get('id'));
        if ($multiContent = $this->accountType->where('existing_record_id', $accountType)->first()) {
            $this->accountType->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->accountType->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->accountType->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->accountType->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->accountType->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->accountType->find($id);
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
                $other_posts = $this->accountType->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->accountType->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->accountType->update($record->id, ['banner' => $content->banner, 'image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function enquiry($id)
    {
        $accountType = $this->accountType->find($id);
        $enquiries = $this->productEnquiry->where('account_type_id', $id)->orderBy('id', 'desc')->get();
        return view('admin.accountType.enquiry', ['accountType' => $accountType, 'enquiries' => $enquiries]);
    }

    public function export()
    {
        $account_types = $this->accountType->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();

        return Excel::download(new LeadExport($account_types), date('Y-m-d') . '-leads.xlsx');
    }
}