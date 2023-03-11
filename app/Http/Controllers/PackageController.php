<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\PackageRouteController;
use App\Http\Requests\Admin\PriceRangeStoreRequest;
use App\Repositories\ActivityFaqRepository;
use App\Repositories\PackagePriceRangeRepository;
use Illuminate\Http\Request;
use App\Repositories\ActivityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\FixedDepartureRepository;
use App\Repositories\ItineraryRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PackageBannerRepository;
use App\Repositories\PackageRouteRepository;
use SEOMeta;
use OpenGraph;
use Twitter;

class PackageController extends Controller
{
   protected $activity,$banner,$country,$fixed_departure,$itinerary,$package,$route_map;
   public function __construct(
                               ActivityRepository $activity,
                               ActivityFaqRepository $faq,
                               CountryRepository $country,
                               FixedDepartureRepository $fixed_departure,
                               PackagePriceRangeRepository $group_price_range,
                               ItineraryRepository $itinerary,
                               PackageRepository $package,
                               PackageRouteRepository $route_map,
                               PackageBannerRepository $banner)
   {
     $this->activity = $activity;
     $this->banner = $banner;
     $this->country = $country;
     $this->faq = $faq;
     $this->fixed_departure = $fixed_departure;
     $this->group_price_range = $group_price_range;
     $this->itinerary = $itinerary;
     $this->package = $package;
     $this->route_map = $route_map;
   }

   public function show(Request $request,$slug){
        $countries = $this->country->all();
        $package = $this->package->where('slug', $slug)->where('is_active',1)->first();
        $similarpackage = $this->package->where('is_active', 1)->get();

       if($package) {
           $gropuPriceRange = $this->group_price_range->where('package_id', '=', $package->id)->orderBy('traveller_range', 'asc')->where('is_active', 1)->paginate(15);

           if (($request->year_value != '')&&($request->month_value == '')){
               $fixeddepartures_list = $this->fixed_departure->get_fixed_departures($package, $request->year_value,null);
           }
           elseif(($request->year_value != '')&&($request->month_value != ''))
           {
               $fixeddepartures_list = $this->fixed_departure->get_fixed_departures($package, $request->year_value,$request->month_value);
           }
           else{
               $fixeddepartures_list = $this->fixed_departure->where('package_id', $package->id)->where('is_active', 1)->paginate(15);
            }
           $fixeddepartures = $this->fixed_departure->where('package_id', $package->id)->where('is_active', 1)->get();
//                  ->where('package_id', $package->id)->where('is_active', 1)->get();

            $itineraries = $this->itinerary->where('is_active', 1)->where('is_active', 1)->where('package_id', $package->id)->get();
            $packagebanner = $this->banner->where('package_id', $package->id)->where('is_active', 1)->get();

            SEOMeta::setTitle($package->title);
            SEOMeta::setDescription($package->description);
            // SEOMeta::addMeta('package:published_time', $package->created_at->toW3CString(), 'property');
            SEOMeta::addMeta('package:section', $package->category, 'property');
            SEOMeta::addKeyword(['key1', 'key2', 'key3']);

            OpenGraph::setDescription($package->description);
            OpenGraph::setTitle($package->title);
            OpenGraph::setUrl('http://current.url.com');
            OpenGraph::addProperty('type', 'package');
            OpenGraph::addProperty('locale', 'pt-br');
            OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

            // OpenGraph::addImage($package->cover->url);add image
            // OpenGraph::addImage($package->images->list('url'));
            OpenGraph::addImage(['url' => 'http://image.url.com/cover.jpg', 'size' => 300]);
            OpenGraph::addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

            // Namespace URI: http://ogp.me/ns/package#
            // package
            OpenGraph::setTitle('Article')
                ->setDescription('Some Article')
                ->setType('article')
                ->setArticle([
                    'published_time' => 'datetime',
                    'modified_time' => 'datetime',
                    'expiration_time' => 'datetime',
                    'author' => 'profile / array',
                    'section' => 'string',
                    'tag' => 'string / array'
                ]);

            Twitter::setTitle('Homepage');
            Twitter::setSite('@LuizVinicius73');

            return view('package.show')
                ->withCountries($countries)
                ->withFixeddepartures($fixeddepartures)
                ->withFixeddeparturesList($fixeddepartures_list)
//                ->withFaqs($this->faq->where('is_active',1)->get())
                ->withGropuPriceRange($gropuPriceRange)
                ->withItineraries($itineraries)
                ->withPackage($package)
                ->withFaqs($this->faq->where('faq_activity_id','=',$package->activity_id)->where('is_active',1)->get())
                ->withRequestData($request->all())
                ->withPackagebanner($packagebanner)
                ->withSimilarpackage($similarpackage)
                ->withRoutemap($this->route_map->where('package_id',$package->id)->where('is_active', 1)->first());
        }else
            {
                return view('package.package_not_found')
                ->withSimilarpackage($similarpackage);
            }

        }
}
