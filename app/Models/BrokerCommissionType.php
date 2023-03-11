<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerCommissionType extends Model
{
    protected $table = 'broker_commission_types';
    protected $fillable  = ['type', 'commission'];
}
