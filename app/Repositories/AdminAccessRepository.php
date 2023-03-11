<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 11/11/18
 * Time: 11:41 AM
 */

namespace App\Repositories;

use App\Models\AdminAccess;

class AdminAccessRepository extends Repository
{
    public function __construct(AdminAccess $adminAccess)
    {
        $this->model = $adminAccess;
    }
}