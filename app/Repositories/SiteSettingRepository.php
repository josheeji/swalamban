<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 12/4/18
 * Time: 12:29 PM
 */

namespace App\Repositories;


use App\Models\SiteSetting;

class SiteSettingRepository extends Repository
{
    public function __construct(SiteSetting $setting)
    {
        $this->model = $setting;
    }

     public function updateByField($field, $value){
        $update = $this->model->where('key', $field)->first();
        $update->fill(['value' => $value])->save();
        return $update;
    }

    public function findValueByKey($field){
        $value = $this->model->where('key', $field)->value('value');
        return $value;
    }

    public function updateMultiField($field, $value, $language)
    {
        $update = $this->model->where('key', $field)->where('language_id', $language)->first();
        // dd($update);
        if (isset($update)) {
            $update->fill(['value' => $value])->save();
            return $update;
        }
    }



}