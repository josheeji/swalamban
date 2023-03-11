<?php

namespace App\Http\Controllers;

use App\Repositories\MembersRepository;
use App\Repositories\SettingRepository;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected   $setting,$member,$package;

    public function __construct(SettingRepository $setting,
                                MembersRepository $member,
                                PackageRepository $package)
    {
        $this->setting =  $setting;
        $this->member = $member;
        $this->package = $package;
    }

    public function getsetting($name){
      $setting= $this->setting->where('name', $name)->first();
      return view('frontend.setting')
      ->withSetting($setting);
    }
    public function getmember(Request $request){
        $perpage = '9';
        $members = $this->member->orderBy('created_at', 'desc')->paginate($perpage);
        return view('frontend.memberlist')->withMembers($members);
    }

     public function Search(Request $request)
     {
        $destination_id = $request->destination_id;
        $activity_id = $request->activity_id;
        $duration_id = $request->duration_id;
        $cost_id = $request->cost_id;
        $first_date = null;
        $second_date = null;
        $first_price = null;
        $second_price = null;
        if($duration_id == 1){$first_date = 1;$second_date = 5;}
        elseif ($duration_id == 2){$first_date = 5;$second_date = 10;}
        elseif ($duration_id == 3){$first_date = 10;$second_date = 15;}
        elseif ($duration_id == 4){$first_date = 15;$second_date = 20;}
        elseif ($duration_id == 5){$first_date = 20;$second_date = 25;}
        elseif ($duration_id == 6){$first_date = 25;$second_date = 30;}
        else                      {$first_date = 30;$second_date = 100;}

          if($cost_id == 1){$first_price = 100;$second_price = 500;}
        elseif ($cost_id == 2){$first_price = 500;$second_price = 1000;}
        elseif ($cost_id == 3){$first_price = 1000;$second_price = 1500;}
        elseif ($cost_id == 4){$first_price = 1500;$second_price = 2000;}
        elseif ($cost_id == 5){$first_price = 2000;$second_price = 2500;}
        elseif ($cost_id == 6){$first_price = 2500;$second_price = 3000;}
        else                      {$first_price = 3000;$second_price = 1000000;}

        $packages = $this->package->filter_package($destination_id,$activity_id,$duration_id,$first_date,$second_date,$cost_id,$first_price,$second_price);
       return view('frontend.search')
       ->withPackages($packages);

        


}
}
