<?php
/**
 * User: rajan
 * Date: 2/19/20
 * Time: 10:36 AM
 */

namespace App\Repositories;

use App\Models\ForexOrder;

class ForexOrderRepository extends Repository
{
    public function __construct(ForexOrder $forexOrder)
    {
        $this->model = $forexOrder;
    }
}