<?php

namespace App\Repositories;

use App\Models\AgmReportCategory;

class AgmReportCategoryRepository extends Repository
{
    public function __construct(AgmReportCategory $agmReportCategory)
    {
        $this->model = $agmReportCategory;
    }
}
