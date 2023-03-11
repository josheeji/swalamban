<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBanner extends Model
{
    protected $fillable = ['package_id','title','description','caption','display_order', 'image', 'link', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by', 'status_by', 'deleted_by'];
}
