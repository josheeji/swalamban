<?php
/**
 * Created by PhpStorm.
 * User: amit
 * Date: 1/14/20
 * Time: 5:03 PM
 */

namespace App\Repositories;


use App\RemitAllianceRequest;

class RemittanceAllianceRequestRepository extends Repository
{
    public function __construct(RemitAllianceRequest $remitRequest)
    {
        $this->model = $remitRequest;
    }
}