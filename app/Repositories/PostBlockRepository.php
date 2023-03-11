<?php


namespace App\Repositories;

use App\Models\PostBlock;
use App\Repositories\Repository;

class PostBlockRepository extends Repository
{
    public function __construct(PostBlock $block)
    {
        $this->model = $block;
    }
}
