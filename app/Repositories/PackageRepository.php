<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\Package;
use App\Classes\SiteMapGenerator;
use DB;

class PackageRepository extends Repository
{
    public function __construct(Package $package,SiteMapGenerator $site_map)
    {
        $this->model = $package;
        $this->site_map = $site_map;
    }

     public function create($input)
     {
         $package = $this->model->create($input);
         $package->seo()->create(['page' => $package->title]);
         $url_route = route('page.package.details', $package->slug);
         $this->site_map->generate($url_route);
         return true;
     }

    public function update($id,$input){

        $this->model->where('id',$id)->update($input);
        return true;
    }


    public function filter_package($destination_id,$activity_id,$duration_id,$first_date,$second_date,$cost_id,$first_price,$second_price)
    {
      
        $data =   $this->model->where('is_active',1)

        ->when(!is_null($destination_id), function ($q) use ($destination_id) {
                $q->where('destination_id', $destination_id);
            })
         ->when(!is_null($activity_id), function ($q) use ($activity_id) {
                $q->where('activity_id', $activity_id);
            })

          ->when(!is_null($duration_id), function ($q) use ($first_date,$second_date) {
                $q->whereBetween('duration', [$first_date, $second_date]);
            })

           ->when(!is_null($cost_id), function ($q) use ($first_price,$second_price) {
                $q->whereBetween('cost', [$first_price, $second_price]);
            })


        ->get();
        
        return $data;
    }

      public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $package = $this->model->findOrFail($id);
           if($package){
                $package->seo()->delete();
                $package->delete();
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