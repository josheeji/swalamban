<?php

namespace App\Models;

use App\Helper\ConstantHelper;
use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use ModelEventLogger;

    protected $fillable = [
        'branch_id',
        'department_id',
        'full_name',
        'email',
        'mobile',
        'subject',
        'message',
        'existing_customer',
        'grant_authorization'
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

    public function branch()
    {
        return $this->belongsTo(BranchDirectory::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    protected static function referenceId()
    {
        $unique = false;
        $code = 1;
        $ref = ConstantHelper::GRIEVENCE_CODE . '-' . str_pad($code, 8, 0, STR_PAD_LEFT);
        while (!$unique) {
            $grievance = Grievance::where('reference_id', $ref)->first();
            if ($grievance) {
                $code += 1;
                $ref = ConstantHelper::GRIEVENCE_CODE . '-' . str_pad($code, 8, 0, STR_PAD_LEFT);
            } else {
                $unique = true;
            }
        }
        return $ref;
    }
}
