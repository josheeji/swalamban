<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 1/22/19
 * Time: 2:46 PM
 */

namespace App\Repositories;

use App\Models\FaqCategory;

class FaqCategoryRepository extends Repository
{
    public function __construct(FaqCategory $category)
    {
        $this->model = $category;
    }
}