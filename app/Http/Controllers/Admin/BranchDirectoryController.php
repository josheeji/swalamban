<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchDirectory\BranchDirectoryStoreRequest;
use App\Http\Requests\Admin\BranchDirectory\BranchDirectoryUpdateRequest;
use App\Repositories\BranchDirectoryRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;

class BranchDirectoryController extends Controller
{

    protected $branchDirectory, $preferredLanguage, $province, $district;

    public function __construct(BranchDirectoryRepository $branchDirectory, ProvinceRepository $province, DistrictRepository $district)
    {
        $this->branchDirectory = $branchDirectory;
        $this->province = $province;
        $this->district = $district;
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
        $this->authorize('master-policy.perform', ['branch-directory', 'view']);
        $branchDirectories = $this->branchDirectory->with(['province', 'district'])->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.branchDirectory.index', ['branchDirectories' => $branchDirectories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['branch-directory', 'add']);
        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $districts = $this->district->where('language_id', $this->preferredLanguage)->get();
        return view('admin.branchDirectory.create', ['provinces' => $provinces, 'districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchDirectoryStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['branch-directory', 'add']);
        $data = $request->except(['_token', 'photo']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        if ($request->hasFile('photo')) {
            $filelocation = MediaHelper::upload($request->file('photo'), 'network-points', true);
            $data['photo'] = $filelocation['storage'];
        }

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['address'] = $data['address'][$preferred_language];
        $preferred_language_item['ward_no'] = $data['ward_no'][$preferred_language];
        $preferred_language_item['phone'] = $data['phone'][$preferred_language];
        $preferred_language_item['mobile'] = $data['mobile'][$preferred_language];
        $preferred_language_item['fullname'] = $data['fullname'][$preferred_language];
        $preferred_insert = $this->branchDirectory->create($preferred_language_item);
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
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['address'] = $data['address'][$language_id];
                        $lang_items[$count]['ward_no'] = $data['ward_no'][$language_id];
                        $lang_items[$count]['phone'] = $data['phone'][$language_id];
                        $lang_items[$count]['mobile'] = $data['mobile'][$language_id];
                        $lang_items[$count]['fullname'] = $data['fullname'][$language_id];
                        $lang_items[$count]['url'] = $preferred_insert->url;
                        $lang_items[$count]['province_id'] = $this->branchDirectory->model()->multiProvince($data['province_id'], $language_id);
                        $lang_items[$count]['district_id'] = $this->branchDirectory->model()->multiDistrict($data['district_id'], $language_id);
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->branchDirectory->model()->insert($lang_items);
            }
            return redirect()->route('admin.branch-directory.index')
                ->with('flash_notice', 'Branch Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Branch can not be created.');
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
        $this->authorize('master-policy.perform', ['branch-directory', 'edit']);
        $branchDirectory = $this->branchDirectory->find($id);

        $lang_content = $this->branchDirectory->where('existing_record_id', $branchDirectory->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $districts = $this->district->where('province_id', $branchDirectory->province_id)->get();

        return view('admin.branchDirectory.edit')
            ->withbranchDirectory($branchDirectory)
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage)
            ->withProvinces($provinces)
            ->withDistricts($districts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchDirectoryUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['branch-directory', 'edit']);
        $atmLocation = $this->branchDirectory->find($id);
        $data = $request->except(['photo']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if ($request->hasFile('photo')) {
            if (file_exists('storage/thumbs/' . $atmLocation->photo) && $atmLocation->photo != '') {
                MediaHelper::destroy($atmLocation->photo);
            }
            $filelocation = MediaHelper::upload($request->file('photo'), 'network-points', true);
            $data['photo'] = $filelocation['storage'];
        }
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->branchDirectory->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['address'] = $data['address'][$language_id];
                    $lang_items['ward_no'] = $data['ward_no'][$language_id];
                    $lang_items['phone'] = $data['phone'][$language_id];
                    $lang_items['mobile'] = $data['mobile'][$language_id];
                    $lang_items['fullname'] = $data['fullname'][$language_id];
                    $lang_items['province_id'] = $this->branchDirectory->model()->multiProvince($data['province_id'], $language_id);
                    $lang_items['district_id'] = $this->branchDirectory->model()->multiDistrict($data['district_id'], $language_id);

                    $this->branchDirectory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.branch-directory.index')
                ->with('flash_notice', 'Branch updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'Branch can not be updated.');
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
        $this->authorize('master-policy.perform', ['branch-directory', 'delete']);
        $this->branchDirectory->where('existing_record_id', $id)->delete();
        $branchDirectory = $this->branchDirectory->find($id);
        if ($this->branchDirectory->destroy($branchDirectory->id)) {
            $message = 'Branch deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['branch-directory', 'changeStatus']);
        $branchDirectory = $this->branchDirectory->find($request->get('id'));
        $status = $branchDirectory->is_active == 0 ? 1 : 0;
        $message = $branchDirectory->is_active == 0 ? 'Branch is published.' : 'Branch is unpublished';
        $this->branchDirectory->changeStatus($branchDirectory->id, $status);
        $this->branchDirectory->update($branchDirectory->id, array('updated_by' => auth()->id()));
        $updated = $this->branchDirectory->find($request->get('id'));
        if ($multContent = $this->branchDirectory->where('existing_record_id', $branchDirectory->id)->first()) {
            $this->branchDirectory->changeStatus($multContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->branchDirectory->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->branchDirectory->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->branchDirectory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->branchDirectory->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function getDistrict(Request $request)
    {
        $province = $request->get('province');
        $data = $this->district->where('province_id', $province)
            ->get();
        $output = '<option value="">Select District</option>';

        foreach ($data as $row) {
            $output .= '<option value="' . $row->id . '">' . $row->title . '</option>';
        }

        echo $output;
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->branchDirectory->find($id);
        if ($content) {
            MediaHelper::destroy($content->photo);
            $content->photo = null;
        }
        if ($content->save()) {
            $preferred_language = $this->preferredLanguage;
            $other_posts = $this->branchDirectory->where('existing_record_id', $content->id)->get();
            $other_posts_grouped_language = $other_posts->groupBy('language_id');
            $english_sort = $this->branchDirectory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
            if ($other_posts_grouped_language) {
                foreach ($other_posts_grouped_language as $language => $records) {
                    foreach ($records as $record) {
                        $this->branchDirectory->update($record->id, ['photo' => $content->photo]);
                    }
                }
            }
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}