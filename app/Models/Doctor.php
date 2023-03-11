<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'prefix',
        'designation',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'show_email',
        'show_phone',
        'degree',
        'area_of_expertise',
        'excerpt',
        'education',
        'training',
        'is_active',
        'display_order',
        'image',
        'department_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $name = !empty($model->prefix) ? $model->prefix . ' ' : '';
            $name .= ' ' . $model->first_name;
            $name .= !empty($model->middle_name) ? ' ' . $model->middle_name : '';
            $name .= !empty($model->last_name) ? ' ' . $model->last_name : '';
            $model->full_name = Helper::slug($name);
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $name = !empty($model->prefix) ? $model->prefix . ' ' : '';
            $name .= $model->first_name;
            $name .= !empty($model->middle_name) ? ' ' . $model->middle_name : '';
            $name .= !empty($model->last_name) ? ' ' . $model->last_name : '';
            $model->full_name = Helper::slug($name);
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    public function timeSlot()
    {
        return $this->hasMany('App\Models\DoctorTimeSlots', 'doctor_id');
    }

    public function appointment()
    {
        return $this->hasMany('App\Models\Appointment', 'doctor_id');
    }

    public function getFullName()
    {
        $name = $this->first_name;
        $name .= !empty($this->middle_name) ? ' ' . $this->middle_name : '';
        $name .= !empty($this->last_name) ? ' ' . $this->last_name : '';
        return $name;
    }
}
