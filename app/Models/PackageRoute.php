<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageRoute extends Model
{
    protected  $table = "package_route";
    protected $fillable = ['package_id','image', 'route_link', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = [
        'created_at',
        'published_date'
    ];
}
