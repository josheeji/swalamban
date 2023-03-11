<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlots extends Model
{
    protected $fillable = [
        'day',
        'shift',
        'from',
        'to',
        'is_fulltime',
        'is_active',
        'doctor_id'
    ];

    public function doctor()
    {
        return $this->belongsTo('App\Models\Doctor', 'doctor_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->from = Helper::formatTime($model->from, 1);
            $model->to = Helper::formatTime($model->to, 1);
            $model->created_by = auth()->user()->id;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $model->from = Helper::formatTime($model->from, 1);
            $model->to = Helper::formatTime($model->to, 1);
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

    public function appointment()
    {
        return $this->hasMany('App\Models\Appointment', 'doctor_time_slot_id');
    }
}
