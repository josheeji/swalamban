<?php

/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\Placement;

class PlacementRepository extends Repository
{
    public function __construct(Placement $place)
    {
        $this->model = $place;
    }
}
