<?php
/**
 * Created by PhpStorm.
 * User: amit
 * Date: 1/23/20
 * Time: 10:36 AM
 */

namespace App\Repositories;


use App\Models\Forex;

class ForexRepository extends Repository
{
    public function __construct(Forex $forex)
    {
        $this->model = $forex;
    }
}