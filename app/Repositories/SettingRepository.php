<?php


namespace App\Repositories;


use App\Models\Members;
use App\Models\MemberType;
use App\Models\Setting;

class SettingRepository extends Repository
{
    public function __construct(Setting $setting)
    {
        $this->model =  $setting;
    }
}