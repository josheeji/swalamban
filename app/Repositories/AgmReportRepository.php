<?php

namespace App\Repositories;

use App\Models\AgmReport;

class AgmReportRepository extends Repository
{
    public function __construct(AgmReport $agmReport)
    {
        $this->model = $agmReport;
    }
}
