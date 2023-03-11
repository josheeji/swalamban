<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\PackageRouteController;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;
use App\Repositories\PackagePriceRangeRepository;

class SelectPriceRangeController extends Controller
{
    protected $price_range;

    public function __construct(
        PackagePriceRangeRepository $price_range,
        PackageRepository $packages)
    {
        $this->price_range = $price_range;
        $this->packages = $packages;
    }

    public function select_price_range($package_Id, $id)
    {
        if ($id == 1 || $id == 2) {
            $price_range = $this->price_range
                ->where('package_id', '=', $package_Id)
                ->where('traveller_range', '=', $id)
                ->where('is_active', 1)
                ->first();
        } elseif ($id == 3 || $id == 4 || $id == 5 || $id == 6) {
            $price_range = $this->price_range
                ->where('package_id', '=', $package_Id)
                ->where('traveller_range', '=', 3)
                ->where('is_active', 1)
                ->first();
        } elseif ($id == 7 || $id == 8 || $id == 9 || $id == 10 || $id == 11 || $id == 12) {
            $price_range = $this->price_range
                ->where('package_id', '=', $package_Id)
                ->where('traveller_range', '=', 4)
                ->where('is_active', 1)
                ->first();
        } else{
        $price_range = $this->price_range
            ->where('package_id', '=', $package_Id)
            ->where('traveller_range', '=', 5)
            ->where('is_active', 1)
            ->first();

    }if($price_range){
        return json_decode($price_range->amount);
        }else
            {
                $package = $this->packages->where('id', '=', $package_Id)->where('is_active',1)->first();
                $total_price = ($package->cost);
                return json_decode($total_price);

            }
    }
}
