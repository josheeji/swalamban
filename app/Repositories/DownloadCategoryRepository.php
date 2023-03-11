<?php

namespace App\Repositories;

use App\Models\DownloadCategory;

class DownloadCategoryRepository extends Repository
{
    public function __construct(DownloadCategory $downloadCategory)
    {
        $this->model = $downloadCategory;
    }
}
