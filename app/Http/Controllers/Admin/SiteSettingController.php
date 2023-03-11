<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Repositories\SiteSettingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{

    protected  $setting;


    public function __construct(SiteSettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $general = $this->setting->where('key_group', 'general')->get();
        $contact = $this->setting->where('key_group', 'contact')->get();
        $social = $this->setting->where('key_group', 'social')->get();
        $remittance = $this->setting->where('key_group', 'remittance')->get();
        $grievance =  $this->setting->where('key_group', 'grievance')->get();
        $others = $this->setting->where('key_group', 'others')->get();
        $schema = $this->setting->where('key_group', 'schema')->get();
        $schemaHome = $this->setting->where('key_group', 'schema_home')->get();
        $banners = $this->setting->where('key_group', 'page_banner')->get();
        $information_officer = $this->setting->where('key_group', 'information_officer')->get();
        return view('admin.siteSetting.index')
            ->withGeneral($general)
            ->withContact($contact)
            ->withSocial($social)
            ->withRemittance($remittance)
            ->withGrievance($grievance)
            ->withOthers($others)
            ->withSchema($schema)
            ->withSchemaHome($schemaHome)
            ->withBanners($banners)
            ->withInformationOfficer($information_officer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', 'grievance_image', 'remit_banner', 'setting');
        // dd($data);
        foreach ($data as $key => $value) {
            if ($key == 'custom_css') {
                $value = json_encode($value);
            }
            $this->setting->updateByField($key, $value);
        }
        if ($request->hasFile('grievance_image')) {
            $image = $this->setting->where('key', 'grievance_image')->first();
            MediaHelper::destroy($image->value);
            $filelocation = MediaHelper::upload($request->file('grievance_image'), 'settings', true, true);
            $this->setting->updateByField('grievance_image', $filelocation['storage']);
        }
        if ($request->hasFile('remit_banner')) {
            $image = $this->setting->where('key', 'remit_banner')->first();
            MediaHelper::destroy($image->value);
            $filelocation = MediaHelper::upload($request->file('remit_banner'), 'settings', true, true);
            $this->setting->updateByField('remit_banner', $filelocation['storage']);
        }
        if ($request->hasFile('management_team_banner')) {
            $image = $this->setting->where('key', 'management_team_banner')->first();
            MediaHelper::destroy($image->value);
            $filelocation = MediaHelper::upload($request->file('management_team_banner'), 'settings', true, true);
            $this->setting->updateByField('management_team_banner', $filelocation['storage']);
        }
        if (!$request->post('multi_language')) {
            $this->setting->updateByField('multi_language', null);
        }
        if (!$request->post('multi_language_front')) {
            $this->setting->updateByField('multi_language_front', null);
        }
        if (!$request->post('product_popup')) {
            $this->setting->updateByField('product_popup', null);
        }

        if ($request->hasFile('information_image')) {
            $image = $this->setting->where('key', 'information_image')->first();
            MediaHelper::destroy($image->value);
            $filelocation = MediaHelper::upload($request->file('information_image'), 'settings', true, true);
            $this->setting->updateByField('information_image', $filelocation['storage']);
        }
        if ($request->hasFile('information_reinsurer_image')) {
            $image = $this->setting->where('key', 'information_reinsurer_image')->first();
            MediaHelper::destroy($image->value);
            $filelocation = MediaHelper::upload($request->file('information_reinsurer_image'), 'settings', true, true);
            $this->setting->updateByField('information_reinsurer_image', $filelocation['storage']);
        }

        $lang = $request->get('setting');
        foreach ($lang as $key => $value) {
            foreach ($value as $index => $item) {
                $this->setting->updateMultiField($index, $item, $key);
            }
        }

        /**
         * Reset session with new saved setting.
         */
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        session()->put('site_settings', $settings);

        return redirect()->route('admin.setting.index')
        ->with('flash_notice', 'Setting updated successfully');
    }


    public function destroy($id)
    {
        $setting = $this->setting->find($id);
        MediaHelper::destroy($setting->value);
        $setting->value = '';
        if (!$setting->save()) {
            dd('test');
        }
        $message = 'Image deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }
}