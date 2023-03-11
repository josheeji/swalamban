<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Repository;

class CountryRepository extends Repository
{
    public function __construct(Country $country)
    {
        $this->model = $country;
    }

    public function insert($inputs)
    {
        return $this->model->insert($inputs);
    }
}