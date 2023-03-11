<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\Itinerary;

class ItineraryRepository  extends Repository
{
    public function __construct(Itinerary $itinerary)
    {
        $this->model = $itinerary;
    }

    public function create($input){
        $this->model->create($input);
        return true;
    }

    public function update($id,$input){

        $this->model->where('id',$id)->update($input);
        return true;
    }
}