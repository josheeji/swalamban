<?php

namespace App\Http\Controllers;

use App\Repositories\ItineraryRepository;
use Illuminate\Http\Request;
use App\Repositories\PackageRepository;
use App\Mail\SendMailToFriend;
use PDF;
use Mail;


class PackageMailToFriendController extends Controller
{
    protected $itinerary,$package;
    public function __construct(
        PackageRepository $package,
        ItineraryRepository $itinerary)
    {
        $this->package = $package;
        $this->itinerary = $itinerary;
    }

    public function send_mail(Request $request){
        $package = $this->package->where('id',$request->package_id)->where('is_active',1)->first();
        if($package)
        {
            $sender_email = $request->sender_email;
            Mail::to($request->receiver_email)
                ->send(new SendMailToFriend($package,$sender_email));
            return redirect()->route('page.package.details',$package->slug)->with('recommend-success', 'Recommendation Send SuccessFully');
        }else{
            return redirect()->back()->withInput()->with('recommend-success', 'Recommendation Cannot be Send SuccessFully');
        }

    }
}
