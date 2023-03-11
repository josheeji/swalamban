<?php

namespace App\Repositories;

use App\Models\AtmLocation;

class AtmLocationRepository extends Repository
{
    public function __construct(AtmLocation $atmLocation)
    {
        $this->model = $atmLocation;
    }
}
