<?php

namespace App\Repositories;

use App\Models\FinancialReportCategory;

class FinancialReportCategoryRepository extends Repository
{
    public function __construct(FinancialReportCategory $financialReportCategory)
    {
        $this->model = $financialReportCategory;
    }
}
