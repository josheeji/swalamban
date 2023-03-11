<?php

namespace App\Repositories;

use App\Models\Career;

class CareerRepository extends Repository
{
    public function __construct(Career $career)
    {
        $this->model = $career;
    }
}
