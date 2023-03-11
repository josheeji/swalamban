<?php

namespace App\Repositories;

use App\Models\BranchDirectory;

class BranchDirectoryRepository extends Repository
{
    public function __construct(BranchDirectory $branchDirectory)
    {
        $this->model = $branchDirectory;
    }
}
