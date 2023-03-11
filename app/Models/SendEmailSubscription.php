<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendEmailSubscription extends Model
{

    protected $table = 'send_email_subscriptions';

    protected $fillable = [
        'subscriber_id',
        'message'
    ];

    public function fixed_departure()
    {
        return $this->belongsTo('App\Models\NewsSubscribe', 'subscriber_id');
    }
}
