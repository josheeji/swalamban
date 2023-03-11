<?php

namespace App\Repositories;

use App\Models\Grievance;

class GrievanceRepository extends Repository
{

    public function __construct(Grievance $post)
    {
        $this->model = $post;
    }
}
