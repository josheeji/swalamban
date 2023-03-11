<?php


namespace App\Repositories;

use App\Models\BlogBlock;
use App\Repositories\Repository;

class BlogBlockRepository extends Repository
{
    public function __construct(BlogBlock $block)
    {
        $this->model = $block;
    }
}
