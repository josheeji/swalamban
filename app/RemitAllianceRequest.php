<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemitAllianceRequest extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'email',
        'phone',
        'message',
        'type'
    ];
}
