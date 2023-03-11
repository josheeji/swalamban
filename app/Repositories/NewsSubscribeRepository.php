<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 9/9/18
 * Time: 10:57 AM
 */

namespace App\Repositories;

use App\Models\NewsSubscribe;

class NewsSubscribeRepository extends Repository
{
    public function __construct(NewsSubscribe $newsSubscribe)
    {
        $this->model = $newsSubscribe;
    }
}