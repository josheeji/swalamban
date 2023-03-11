<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSubscribe extends Model
{
    protected $table = 'news_subscribe';

    protected $fillable = ['email', 'is_active'];
}
