<?php

namespace App\Repositories;

use App\Models\ProductEnquiry;

class ProductEnquiryRepository extends Repository
{
    public function __construct(ProductEnquiry $productEnquiry)
    {
        $this->model = $productEnquiry;
    }
}
