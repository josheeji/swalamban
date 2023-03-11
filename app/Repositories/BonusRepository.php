<?php

namespace App\Repositories;

use App\Models\Bonus;
use App\Repositories\Repository;

class BonusRepository extends Repository
{
    public function __construct(Bonus $bonus)
    {
        $this->model = $bonus;
    }
}
