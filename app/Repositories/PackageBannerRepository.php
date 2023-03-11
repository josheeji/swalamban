<?php
namespace App\Repositories;

use App\Models\PackageBanner;

class PackageBannerRepository extends Repository
{
    public function __construct(PackageBanner $banner)
    {
        $this->model = $banner;
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