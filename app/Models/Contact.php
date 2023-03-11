<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected  $table = 'contacts';

    protected $fillable = [
        'f_name',
        'l_name',
        'name',
        'mobile_no',
        'email_address',
        'subject',
        'message',
        'option_1',
        'subject',
        'captcha'
    ];
}
