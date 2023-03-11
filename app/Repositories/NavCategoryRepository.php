<?php

namespace App\Repositories;

use App\Models\NavCategory;

class NavCategoryRepository extends Repository
{
    public function __construct(NavCategory $category)
    {
        $this->model = $category;
    }
}
