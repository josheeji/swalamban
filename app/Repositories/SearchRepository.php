<?php

namespace App\Repositories;

use App\Models\Search;

class SearchRepository extends Repository
{
    public function __construct(Search $search)
    {
        $this->model = $search;
    }
}
