<?php

namespace App\Repositories;

use App\Models\PostCategory;

class PostCategoryRepository extends Repository
{

    public function __construct(PostCategory $post_category)
    {
        $this->model = $post_category;
    }

    public function create($input)
    {
        $this->model->create($input);
        return true;
    }

    public function update($id, $input)
    {
        $this->model->where('id', $id)->update($input);
        return true;
    }
}
