<?php

namespace App\Repositories;

use App\Models\Province;

class ProvinceRepository extends Repository
{
    public function __construct(Province $province)
    {
        $this->model = $province;
    }
}
