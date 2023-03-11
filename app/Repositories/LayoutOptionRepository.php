<?php

namespace App\Repositories;

use App\Models\LayoutOption;

class LayoutOptionRepository extends Repository
{

    public function __construct(LayoutOption $layoutOption)
    {
        $this->model = $layoutOption;
    }
}
