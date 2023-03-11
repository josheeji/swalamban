<?php

namespace App\Repositories;

use App\Models\Nav;

class NavRepository extends Repository
{
    public function __construct(Nav $nav)
    {
        $this->model = $nav;
    }
}
