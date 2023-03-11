<?php

namespace App\Repositories;

use App\Models\TeamCategory;

class TeamCategoryRepository extends Repository
{
    public function __construct(TeamCategory $teamCateogry)
    {
        $this->model = $teamCateogry;
    }
}
