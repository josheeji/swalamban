<?php
namespace App\Repositories;

use App\Models\JyotiCare;
use App\Repositories\Repository;

class JyotiCareRepository extends Repository
{
    public function __construct(JyotiCare $jCare)
    {
        $this->model = $jCare;
    }


    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }

}