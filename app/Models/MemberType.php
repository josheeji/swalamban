<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{

    protected $table = 'member_type';

    protected $fillable = ['name'];

    public function member_type()
    {
        return $this->belongsTo(MemberType::class, '');
    }
}
