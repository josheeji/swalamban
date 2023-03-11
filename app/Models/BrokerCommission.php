<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerCommission extends Model
{
    protected $table = 'broker_commissions';
    protected $fillable  = ['range_from', 'range_to', 'commission'];
}
