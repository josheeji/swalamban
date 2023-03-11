<?php

namespace App\Repositories;

use App\Models\AccountTypeCategory;

class AccountTypeCategoryRepository extends Repository
{
    public function __construct(AccountTypeCategory $accountType)
    {
        $this->model = $accountType;
    }
}