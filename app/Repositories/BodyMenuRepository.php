<?php

namespace App\Repositories;

use App\Models\BodyMenu;

class BodyMenuRepository extends Repository
{
    public function __construct(BodyMenu $bodyMenu)
    {
        $this->model = $bodyMenu;
    }
}
