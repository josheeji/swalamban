<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Repositories\AtmLocationRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class AtmController extends Controller
{
    protected $atm;
    protected $province;
    protected $district;
    protected $locale_id;

    public function __construct(AtmLocationRepository $atm, ProvinceRepository $province, DistrictRepository $district)
    {
        $this->atm = $atm;
        $this->province = $province;
        $this->district = $district;
        $this->locale_id = session('locale_id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $insideValley = $this->atm->with('province', 'district')->where('is_active', 1)->where('language_id', Helper::locale())->where('inside_valley', 1)->orderBy('display_order', 'asc')->get();
        $outsideValley = $this->atm->with('province', 'district')->where('is_active', 1)->where('language_id', Helper::locale())->where('inside_valley', 0)->orderBy('display_order', 'asc')->get();
        return view('atm.index', [
            'insideValley' => $insideValley,
            'outsideValley' => $outsideValley
        ]);
    }

    public function show($slug)
    {
        $atm = $this->atm->where('slug', $slug)->where('language_id', Helper::locale())->where('is_active', 1)->first();
        if (!$atm) {
            abort('404');
        }
        $schema = Schema::automatedTeller()
            ->name($atm->title)
            ->url(url('atm/' . $atm->slug))
            ->image(asset('kumari/images/logo.png'))
            ->latitude($atm->latitude)
            ->longitude($atm->longitude)
            ->address($atm->address);
        $schema = $schema->toScript();
        return view('atm.show')->withAtm($atm)
            ->withSchema($schema);
    }
}
