<?php

namespace App\Repositories;

use App\Helper\ConstantHelper;
use App\Models\PackageCategory;

class PackageCategoryRepository extends Repository
{
    public function __construct(PackageCategory $package_category)
    {
        $this->model = $package_category;
    }

    public function categoryList($parentId = 0, $level = 0)
    {
        $options = [];
        $categories = $this->model->where('parent_id', $parentId)->where('is_active', 1)->get();
        foreach ($categories as $category) {
            $options[$category->id] = str_repeat('-', $level) . $category->title;
            $options = array_replace($options, $this->categoryList($category->id, $level + 1));
        }
        return $options;
    }

    public function categoryBySlug($slug)
    {
        return $this->model->where('slug', $slug)->where('is_active', ConstantHelper::IS_ACTIVE)->first();
    }
}
