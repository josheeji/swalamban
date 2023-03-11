<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Remittance\RemittanceStoreRequest;
use App\Repositories\CountryRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\RemittanceRepository;
use Illuminate\Http\Request;

class RemittanceController extends Controller
{

    public $title = 'Remittance';

    protected $remittance, $preferredLanguage, $province, $district, $country;

    public function __construct(
        RemittanceRepository $remittance,
        ProvinceRepository $province,
        DistrictRepository $district,
        CountryRepository $country
    ) {
        $this->remittance = $remittance;
        $this->province = $province;
        $this->district = $district;
        $this->country = $country;

        $this->preferredLanguage = session('site_settings')['preferred_language'];

        auth()->shouldUse('admin');
    }

    protected function multiProvince($province_id, $language_id)
    {
        if ($province = $this->province->where('existing_record_id', $province_id)->where('language_id', $language_id)->first()) {
            return $province->id;
        }
        return $province_id;
    }

    protected function multiDistrict($district_id, $language_id)
    {
        if ($district = $this->district->where('existing_record_id', $district_id)->where('language_id', $language_id)->first()) {
            return $district->id;
        }
        return $district_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['remittance', 'view']);
        $title = $this->title;

        $remittance = $this->remittance->where('language_id', $this->preferredLanguage);
        if ($request->has('keyword') && $request->keyword != '') {
            $remittance = $remittance->where('title', 'like', '%' . $request->keyword . '%');
        }
        $remittance = $remittance->orderBy('display_order')->paginate(50);

        return view('admin.remittance.index')
            ->withRemittance($remittance)
            ->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['remittance', 'add']);
        $title = $this->title;

        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $countries = $this->country->all();
        $parent = $this->remittance->whereNull('parent_id')
            ->where('visible_in', ConstantHelper::VISIBLE_IN_REMITTANCE_OVERSEAS)
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.remittance.create')
            ->withProvinces($provinces)
            ->withCountries($countries)
            ->withTitle($title)
            ->withParent($parent);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RemittanceStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['remittance', 'add']);
        $data = $request->except(['_token', 'segment']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['address'] = $data['address'][$preferred_language];
        $preferred_language_item['contact_no'] = $data['contact_no'][$preferred_language];
        $preferred_language_item['country_id'] = $data['country_id'];
        $preferred_language_item['district_id'] = $data['district_id'];
        $preferred_language_item['province_id'] = $data['province_id'];
        $preferred_language_item['relationship_officer'] = $data['relationship_officer'][$preferred_language];

        $preferred_insert = $this->remittance->create($preferred_language_item);

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
                        $lang_items[$count]['contact_no'] = $data['contact_no'][$language_id];
                        $lang_items[$count]['province_id'] = $this->multiProvince($data['province_id'], $language_id);
                        $lang_items[$count]['district_id'] = $this->multiDistrict($data['district_id'], $language_id);
                        $lang_items[$count]['country_id'] = $data['country_id'];
                        $lang_items[$count]['relationship_officer'] = $data['relationship_officer'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['visible_in'] = implode(',', $data['visible_in']);

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->remittance->model()->insert($lang_items);
            }
            switch ($preferred_insert->visible_in) {
                case ConstantHelper::VISIBLE_IN_REMITTANCE_KUMARI:
                    $route = 'admin.remit-kumari';
                    break;
                case ConstantHelper::VISIBLE_IN_REMITTANCE_LOCAL;
                    $route = 'admin.remit-service';
                    break;
                case ConstantHelper::VISIBLE_IN_REMITTANCE_OVERSEAS;
                    $route = 'admin.remit-oversea-alliance';
                    break;
                default:
                    $route = 'admin.remittance.index';
                    break;
            }

            return redirect()->route($route)
                ->with('flash_notice', 'Remittance Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Remittance can not be created.');
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
    public function edit($id, Request $request)
    {
        $this->authorize('master-policy.perform', ['remittance', 'edit']);
        $title = $this->title;
        $remittance = $this->remittance->find($id);
        $countries = $this->country->all();

        $lang_content = $this->remittance->where('existing_record_id', $remittance->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        $provinces = $this->province->where('language_id', $this->preferredLanguage)->get();
        $districts = $this->district->where('province_id', $remittance->province_id)->get();
        $parent = $this->remittance->whereNull('parent_id')
            ->where('visible_in', ConstantHelper::VISIBLE_IN_REMITTANCE_OVERSEAS)
            ->where('language_id', $this->preferredLanguage)
            ->orderBy('title', 'asc')
            ->get();
        return view('admin.remittance.edit')
            ->withRemittance($remittance)
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage)
            ->withCountries($countries)
            ->withProvinces($provinces)
            ->withDistricts($districts)
            ->withTitle($title)
            ->withParent($parent)
            ->withType($request->get('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RemittanceStoreRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['remittance', 'edit']);
        $remittance = $this->remittance->find($id);
        $data = $request->except(['image']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->remittance->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['address'] = $data['address'][$language_id];
                    $lang_items['contact_no'] = $data['contact_no'][$language_id];
                    $lang_items['province_id'] = $this->multiProvince($data['province_id'], $language_id);
                    $lang_items['district_id'] = $this->multiDistrict($data['district_id'], $language_id);
                    $lang_items['country_id'] = $data['country_id'];
                    $lang_items['relationship_officer'] = $data['relationship_officer'][$language_id];
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 0 : $existing_record_id->display_order;
                    $result =  $this->remittance->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            switch ($existing_record_id->visible_in) {
                case ConstantHelper::VISIBLE_IN_REMITTANCE_KUMARI:
                    $route = 'admin.remit-kumari';
                    break;
                case ConstantHelper::VISIBLE_IN_REMITTANCE_LOCAL;
                    $route = 'admin.remit-service';
                    break;
                case ConstantHelper::VISIBLE_IN_REMITTANCE_OVERSEAS;
                    $route = 'admin.remit-oversea-alliance';
                    break;
                default:
                    $route = 'admin.remittance.index';
                    break;
            }

            return redirect()->route($route)
                ->with('flash_notice', 'Remittance updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'Remittance can not be updated.');
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
        $this->authorize('master-policy.perform', ['remittance', 'delete']);
        $this->remittance->where('existing_record_id', $id)->delete();
        $remittance = $this->remittance->find($id);
        if ($this->remittance->destroy($remittance->id)) {
            $message = 'Remittance deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['remittance', 'changeStatus']);
        $remittance = $this->remittance->find($request->get('id'));
        $status = $remittance->is_active == 0 ? 1 : 0;
        $message = $remittance->is_active == 0 ? 'Remittance is published.' : 'Remittance is unpublished';
        $this->remittance->changeStatus($remittance->id, $status);
        $this->remittance->update($remittance->id, array('updated_by' => auth()->id()));
        $updated = $this->remittance->find($request->get('id'));
        if ($multiContent = $this->remittance->where('existing_record_id', $remittance->id)->first()) {
            $this->remittance->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->remittance->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->remittance->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->remittance->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->remittance->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
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

    public function overseaAlliance()
    {
        $this->authorize('master-policy.perform', ['remittance', 'view']);
        $title = $this->title . ': Overseas alliance';

        $remittance = $this->remittance->where('language_id', $this->preferredLanguage)->where('visible_in', 'like', '%' . ConstantHelper::VISIBLE_IN_REMITTANCE_OVERSEAS . '%');
        $remittance = $remittance->orderBy('display_order', 'asc')->get();

        return view('admin.remittance.index-data')
            ->withRemittance($remittance)
            ->withTitle($title);
    }

    public function kumari(Request $request)
    {
        $this->authorize('master-policy.perform', ['remittance', 'view']);
        $title = $this->title . ': Kumari paying alliance';

        $remittance = $this->remittance->where('language_id', $this->preferredLanguage);
        if ($request->has('keyword') && $request->keyword != '') {
            $remittance = $remittance->where('title', 'like', '%' . $request->keyword . '%');
            // $remittance = $remittance->with(['province' => function ($q) use ($request) {
            //     $q->where('title', 'like', '%' . $request->keyword . '%');
            // }]);
        }
        $remittance = $remittance->where('visible_in', 'like', '%' . ConstantHelper::VISIBLE_IN_REMITTANCE_KUMARI . '%');
        $remittance = $remittance->orderBy('display_order')->paginate(50);

        return view('admin.remittance.index')
            ->withRemittance($remittance)
            ->withTitle($title);
    }

    public function service()
    {
        $this->authorize('master-policy.perform', ['remittance', 'view']);
        $title = $this->title . ': Remit service';

        $remittance = $this->remittance->where('language_id', $this->preferredLanguage)
            ->where('visible_in', 'like', '%' . ConstantHelper::VISIBLE_IN_REMITTANCE_LOCAL . '%');
        $remittance = $remittance->orderBy('display_order', 'asc')->get();

        return view('admin.remittance.index-data')
            ->withRemittance($remittance)
            ->withTitle($title);
    }

    public function syncData()
    {
        $data = $this->remittance->whereNull('existing_record_id')->get();
        foreach ($data as $remittance) {
            if ($multiContent = $this->remittance->where('existing_record_id', $remittance->id)->first()) {
                $multiContent->display_order = $remittance->display_order;
                $multiContent->is_active = $remittance->is_active;
                $multiContent->save();
            }
        }
    }
}
