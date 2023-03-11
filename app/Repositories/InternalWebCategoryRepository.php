<?php

namespace App\Repositories;

use App\Models\InternalWebCategory;

class InternalWebCategoryRepository extends Repository
{
    public function __construct(InternalWebCategory $internalWebCategory)
    {
        $this->model = $internalWebCategory;
    }
}
