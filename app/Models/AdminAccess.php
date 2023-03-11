<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAccess extends Model
{
    protected $fillable = [
        'admin_type_id',
        'module_id',
        'view',
        'add',
        'edit',
        'delete',
        'changeStatus'
    ];

    public function adminType()
    {
        return $this->belongsTo(AdminType::class, 'admin_type_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
