<?php

namespace App\Repositories;

use App\Models\NewsCategory;

class NewsCategoryRepository extends Repository
{
    public function __construct(NewsCategory $category)
    {
        $this->model = $category;
    }
}
