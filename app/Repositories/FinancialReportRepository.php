<?php

namespace App\Repositories;

use App\Models\FinancialReport;

class FinancialReportRepository extends Repository
{
    public function __construct(FinancialReport $financialReport)
    {
        $this->model = $financialReport;
    }
}
