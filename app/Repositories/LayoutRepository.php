<?php

namespace App\Repositories;

use App\Models\Layout;

class LayoutRepository extends Repository
{

    public function __construct(Layout $layout)
    {
        $this->model = $layout;
    }
}
