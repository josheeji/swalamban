<?php

/**
 * Created by PhpStorm.
 * User: amit
 * Date: 1/10/20
 * Time: 3:13 PM
 */

namespace App\Repositories;


use App\Models\Remittance;

class RemittanceRepository extends Repository
{

    public function __construct(Remittance $remittance)
    {
        $this->model = $remittance;
    }
}
