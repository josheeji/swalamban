<?php

namespace App\Repositories;

use App\Models\District;

class DistrictRepository extends Repository
{
    public function __construct(District $district)
    {
        $this->model = $district;
    }
}
