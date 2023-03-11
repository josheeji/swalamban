<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected  $table = 'members';
    protected $fillable = ['name', 'image', 'contact', 'description', 'member_type_id', 'is_active'];

    public function member_type()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id');
    }
    public function getMembername()
    {

        return $this->member_type ? $this->member_type->name : '';
    }
}
