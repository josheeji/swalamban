<?php

namespace App\Repositories;

use App\Models\LoanGraph;

class LoanGraphRepository extends Repository
{
    public function __construct(LoanGraph $loanGraph)
    {
        $this->model = $loanGraph;
    }
}
