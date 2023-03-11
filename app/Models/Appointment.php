<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'department_id',
        'doctor_id',
        'patient_type',
        'date',
        'doctor_time_slot_id',
        'full_name',
        'gender',
        'dob',
        'address',
        'email',
        'phone',
        'message',
        'is_confirmed',
        'is_active'
    ];

    public function doctor()
    {
        return $this->belongsTo('App\Models\Doctor', 'doctor_id');
    }
    public function timeSlot()
    {
        return $this->belongsTo('App\Models\DoctorTimeSlots', 'doctor_time_slot_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }
}
