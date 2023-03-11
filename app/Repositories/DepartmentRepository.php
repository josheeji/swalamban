<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository extends Repository
{
    public function __construct(Department $department)
    {
        $this->model = $department;
    }

    public function departmentList($parentId = 0, $level = 0)
    {
        $options = [];
        $categories = $this->model->where('parent_id', $parentId)->where('is_active', 1)->get();
        foreach ($categories as $category) {
            $options[$category->id] = str_repeat('-', $level) . $category->title;
            $options = array_replace($options, $this->departmentList($category->id, $level + 1));
        }
        return $options;
    }
}
