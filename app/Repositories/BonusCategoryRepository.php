<?php

namespace App\Repositories;

use App\Models\BonusCategory;
use App\Repositories\Repository;

class BonusCategoryRepository extends Repository
{
    public function __construct(BonusCategory $category)
    {
        $this->model = $category;
    }
}
