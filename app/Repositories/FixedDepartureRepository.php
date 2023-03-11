<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\FixedDeparture;
use Carbon\Carbon;

class FixedDepartureRepository extends Repository
{
    public function __construct(FixedDeparture $fixed_departure)
    {
        $this->model = $fixed_departure;
    }

    public function create($input)
    {
        $this->model->create($input);
        return true;
    }

    public function update($id, $input)
    {

        $this->model->where('id', $id)->update($input);
        return true;
    }

    public function get_fixed_departures($package, $year, $month)
    {

        $fixed_departures = $this->model
            ->when(!is_null($year), function ($q) use ($year) {
                $q->where('year', $year);
            })
            ->when(!is_null($month), function ($q) use ($month) {
                $q->whereMonth('departure_date', '=', $month);
            })
            ->where('package_id', $package->id)->where('is_active', 1)->paginate(15);
        return $fixed_departures;
    }

    public function get_requested_months($package_id,$year_value)
    {
       $months = $this->model->where('package_id',$package_id)
                             ->whereYear('departure_date',$year_value)
                             ->where('is_active',1)
                             ->get();
       return $months;
     }
}