<?php

namespace App\Repositories;

use App\Models\StockInfo;

class StockInfoRepository extends Repository
{
    public function __construct(StockInfo $stockInfo)
    {
        $this->model = $stockInfo;
    }
}
