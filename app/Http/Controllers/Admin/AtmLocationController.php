<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AtmLocation\AtmLocationStoreRequest;
use App\Http\Requests\Admin\AtmLocation\AtmLocationUpdateRequest;
use App\Repositories\AtmLocationRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;

class AtmLocationController extends Controller
{
    protected $atmLocation, $preferredLanguage, $province, $district;

    public function __construct(AtmLocationRepository $atmLocation, ProvinceRepository $province,  DistrictRepository $district)
    {
        $this->atmLocation = $atmLocation;
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
        $this->authorize('master-policy.perform', ['atm-location', 'view']);
        $atmLocations = $this->atmLocation->where('language_id', $this->preferredLanguage)->orderBy('display_order')->get();
        return view('admin.atmLocation.index', ['atmLocations' => $atmLocations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['atm-location', 'add']);
        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $districts = $this->district->where('language_id', $this->preferredLanguage)->get();
        return view('admin.atmLocation.create', ['provinces' => $provinces, 'districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtmLocationStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['atm-location', 'add']);
        $data = $request->except(['_token']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['address'] = $data['address'][$preferred_language];
        $preferred_language_item['ward_no'] = $data['ward_no'][$preferred_language];
        $preferred_insert = $this->atmLocation->create($preferred_language_item);
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
                        $lang_items[$count]['address'] = $data['address'][$language_id];
                        $lang_items[$count]['ward_no'] = $data['ward_no'][$language_id];
                        $lang_items[$count]['province_id'] = $this->atmLocation->model()->multiProvince($data['province_id'], $language_id);
                        $lang_items[$count]['district_id'] = $this->atmLocation->model()->multiDistrict($data['district_id'], $language_id);
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->atmLocation->model()->insert($lang_items);
            }
            return redirect()->route('admin.atm-location.index')->with('flash_success', 'ATM location created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'ATM location can not be created.');
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
        $this->authorize('master-policy.perform', ['atm-location', 'edit']);
        $atmLocation = $this->atmLocation->find($id);
        $lang_content = $this->atmLocation->where('existing_record_id', $atmLocation->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $districts = $this->district->where('province_id', $atmLocation->province_id)->get();

        return view('admin.atmLocation.edit', ['atmLocation' => $atmLocation, 'langContent' => $lang_content, 'preferredLanguage' => $this->preferredLanguage, 'provinces' => $provinces, 'districts' => $districts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtmLocationUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['atm-location', 'edit']);
        $atmLocation = $this->atmLocation->find($id);
        $data = $request->except(['image']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->atmLocation->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['address'] = $data['address'][$language_id];
                    $lang_items['ward_no'] = $data['ward_no'][$language_id];
                    $lang_items['province_id'] = $this->atmLocation->model()->multiProvince($data['province_id'], $language_id);
                    $lang_items['district_id'] = $this->atmLocation->model()->multiDistrict($data['district_id'], $language_id);
                    $this->atmLocation->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.atm-location.index')->with('flash_notice', 'ATM location updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'ATM location can not be updated.');
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
        $this->authorize('master-policy.perform', ['atm-location', 'delete']);
        $this->atmLocation->where('existing_record_id', $id)->delete();
        $atmLocation = $this->atmLocation->find($id);
        if ($this->atmLocation->destroy($atmLocation->id)) {
            $message = 'ATM location deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['atm-location', 'changeStatus']);
        $atmLocation = $this->atmLocation->find($request->get('id'));
        $status = $atmLocation->is_active == 0 ? 1 : 0;
        $message = $atmLocation->is_active == 0 ? 'ATM location is published.' : 'ATM location is unpublished';
        $this->atmLocation->changeStatus($atmLocation->id, $status);
        $this->atmLocation->update($atmLocation->id, array('updated_by' => auth()->id()));
        $updated = $this->atmLocation->find($request->get('id'));
        if ($multiContent = $this->atmLocation->where('existing_record_id', $atmLocation->id)->first()) {
            $this->atmLocation->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->atmLocation->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->atmLocation->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->atmLocation->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->atmLocation->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
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
}
