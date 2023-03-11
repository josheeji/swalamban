<?php

namespace App\Repositories;

use App\Models\Statistics;

class StatisticsRepository extends Repository
{
    public function __construct(Statistics $statistics)
    {
        $this->model = $statistics;
    }
}
