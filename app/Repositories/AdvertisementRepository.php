<?php

/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\Advertisement;

class AdvertisementRepository extends Repository
{
    public function __construct(Advertisement $ad)
    {
        $this->model = $ad;
    }
}
