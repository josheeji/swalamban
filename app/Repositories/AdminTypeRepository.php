<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 11/12/18
 * Time: 10:02 AM
 */

namespace App\Repositories;

use App\Models\AdminType;

class AdminTypeRepository extends Repository
{
    public function __construct(AdminType $adminType)
    {
        $this->model = $adminType;
    }

}