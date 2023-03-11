<?php
namespace App\Repositories;

use App\Models\ImageResize;

class ImageResizeRepository extends Repository
{
    public function __construct(ImageResize $image_resize)
    {
        $this->model = $image_resize;
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