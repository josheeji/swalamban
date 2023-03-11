<?php
namespace App\Repositories;

use App\Models\PackagePriceRange;

class PackagePriceRangeRepository extends Repository
{
    public function __construct(PackagePriceRange $price_range)
    {
        $this->model = $price_range;
    }

    public function create($input){
        $this->model->create($input);
        return true;
    }

    public function update($id,$input){

        $this->model->where('id',$id)->update($input);
        return true;
    }
}