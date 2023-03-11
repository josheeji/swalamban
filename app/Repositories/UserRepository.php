<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/12/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Repository;

class UserRepository extends Repository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

}