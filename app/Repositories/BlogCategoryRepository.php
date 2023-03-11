<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Repositories\Repository;

class BlogCategoryRepository extends Repository
{
    public function __construct(BlogCategory $blogCategory)
    {
        $this->model = $blogCategory;
    }

    public function lists()
    {
        return $this->model->pluck('title', 'id');
    }


}