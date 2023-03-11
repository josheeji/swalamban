<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonials extends Model
{
    protected $table = 'testimonials';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['display_order', 'name', 'designation', 'company_name', 'image', 'description', 'created_at', 'rating', 'is_active', 'existing_record_id', 'banner', 'created_by', 'created_at', 'languge_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at'];
}