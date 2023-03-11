<?php

namespace App\Policies\Admin;

use App\Helper\Helper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MasterAccessPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function perform($user, $module, $action){
        return Helper::isGateSurpassed($module, $action);
    }

    public function performArray($user, $module_array, $action){
        return Helper::isArrayGateSurpassed($module_array, $action);
    }
}
