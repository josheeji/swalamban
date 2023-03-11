<?php

namespace App\Repositories;

use App\Models\AccountType;

class AccountTypeRepository extends Repository
{
    public function __construct(AccountType $accountType)
    {
        $this->model = $accountType;
    }
}
