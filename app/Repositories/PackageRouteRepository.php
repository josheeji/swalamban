<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/7/18
 * Time: 10:33 AM
 */

namespace App\Repositories;

use App\Models\PackageRoute;

class PackageRouteRepository extends Repository
{
    public  function __construct(PackageRoute $packageRoute)
    {
        $this->model = $packageRoute;
    }
}