<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JyotiCare extends Model
{
    protected  $table = 'jyoti_cares';

    protected $fillable = [
        'full_name',
        'mobile_no',
        'email_address',
        'address',
        'qualification',
        'branch',
        'status_category',
        'citizenship_file',
        'remarks',
        'is_active',
        'captcha'
    ];
}