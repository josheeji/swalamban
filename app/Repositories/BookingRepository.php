<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\Repository;

class BookingRepository extends Repository
{
    public function __construct(Booking $Booking)
    {
        $this->model = $Booking;
    }


    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }

}