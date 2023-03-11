<?php


namespace App\Repositories;

use App\Models\ContentBlock;
use App\Repositories\Repository;

class ContentBlockRepository extends Repository
{
    public function __construct(ContentBlock $block)
    {
        $this->model = $block;
    }
}
