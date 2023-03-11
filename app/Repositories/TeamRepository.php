<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository extends Repository
{
    public function __construct(Team $team)
    {
        $this->model = $team;
    }
}
