<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\InterestRate;
use App\Repositories\Repository;

class InterestRatesRepository extends Repository
{
    public function __construct(InterestRate $interestRate)
    {
        $this->model = $interestRate;
    }

    public function createMany($data)
    {
        return $this->model->insert($data);
    }

}