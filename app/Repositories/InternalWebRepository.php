<?php

namespace App\Repositories;

use App\Models\InternalWeb;

class InternalWebRepository extends Repository
{
    public function __construct(InternalWeb $internalWeb)
    {
        $this->model = $internalWeb;
    }
}
