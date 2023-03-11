<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Repository;

class CompanyRepository extends Repository
{
    public function __construct(Company $company)
    {
        $this->model = $company;
    }
}