<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Repository;

class BlogRepository extends Repository
{
    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

}