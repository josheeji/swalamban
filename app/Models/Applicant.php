<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'reference_id',
        'career_id',
        'full_name',
        'email',
        'contact_no',
        'email',
        'address',
        'message',
        'resume',
        'cover_letter',
        'updated_by',
        'p_address',
        't_address'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->reference_id = self::referenceId();
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'career_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    protected static function referenceId()
    {
        $unique = false;
        $code = 1;
        $ref = ConstantHelper::APPLICANT_PREFIX . '-' . str_pad($code, 8, 0, STR_PAD_LEFT);
        while (!$unique) {
            $applicant = Applicant::where('reference_id', $ref)->first();
            if ($applicant) {
                $code += 1;
                $ref = ConstantHelper::APPLICANT_PREFIX . '-' . str_pad($code, 8, 0, STR_PAD_LEFT);
            } else {
                $unique = true;
            }
        }
        return $ref;
    }
}
