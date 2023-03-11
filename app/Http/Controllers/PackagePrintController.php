<?php

namespace App\Http\Controllers;

use App\Repositories\ItineraryRepository;
use Illuminate\Http\Request;
use App\Repositories\PackageRepository;
use PDF;


class PackagePrintController extends Controller
{
    protected $itinerary,$package;
    public function __construct(
        PackageRepository $package,
        ItineraryRepository $itinerary)
    {
        $this->package = $package;
        $this->itinerary = $itinerary;
    }

    public function print($slug){
        $package = $this->package->where('slug',$slug)->where('is_active',1)->first();
        if($package){
        $itineraries = $this->itinerary->where('package_id','=',$package->id)->where('is_active',1)->get();
        $pdf = PDF::loadView('package.print_pdf',compact('package','itineraries'));
        return $pdf->stream();
        }else{
            return view('package.package_not_found')
                ->withSimilarpackage($this->package->where('is_active', 1)->get());
        }
    }
}
