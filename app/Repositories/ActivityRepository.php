<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Classes\SiteMapGenerator;
use App\Models\Activity;
use App\Repositories\Repository;
use DB;

class ActivityRepository extends Repository
{
    public function __construct(Activity $activity,SiteMapGenerator $site_map)
    {
        $this->model = $activity;
        $this->site_map = $site_map;
    }

    public function create($input){
        $activity = $this->model->create($input);
        $activity->seo()->create(['page'=>$activity->title]);
        $url_route = route('page.activity.details', $activity->slug);
        $this->site_map->generate($url_route);
        return true;
    }

    public function update($id,$inputs){
        $activity = $this->model->findOrFail($id);
        $activity->fill($inputs)->save();

//        $url_route = route('page.activity.details', $activity->slug);
//        $this->site_map->update($url_route);

        return $activity;
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $activity = $this->model->findOrFail($id);
           if($activity){
                $activity->seo()->delete();
                $activity->delete();
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