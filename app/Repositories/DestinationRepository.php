<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\Destination;
use App\Repositories\Repository;
use DB;

class DestinationRepository extends Repository
{
    public function __construct(Destination $destination)
    {
        $this->model = $destination;
    }

     public function create($input){
        $destination = $this->model->create($input);
        $destination->seo()->create(['page'=>$destination->name]);
        return true;
    }


    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }

       public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $destination = $this->model->findOrFail($id);
           if($destination){
                $destination->seo()->delete();
                $destination->delete();
                DB::commit();
                return true;
            }
            DB::commit();
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return false;
        }
    }

}