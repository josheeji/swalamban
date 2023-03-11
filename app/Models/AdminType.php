<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminType extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function access()
    {
        return $this->hasMany(AdminAccess::class, 'admin_type_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'admin_accesses', 'admin_type_id', 'module_id');
    }
}
