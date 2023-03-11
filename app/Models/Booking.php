<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected  $table = 'booking';

    protected $fillable = [
        'package_id', 'fixed_departure_id', 'f_name', 'l_name', 'no_person', 'email', 'message', 'rate_amount', 'is_active', 'mobile_no', 'address', 'country_id', 'departure_date', 'return_date', 'display_order'
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function fixed_departure()
    {
        return $this->belongsTo('App\Models\FixedDeparture', 'fixed_departure_id');
    }
}
