<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\ModelEventLogger;

class CheckBankGuarantee extends Model
{

    protected $table = 'check_bank_guarantee';

    protected $fillable = [
        'branch_code',
        'branch_name',
        'ref_no',
        'applicant',
        'beneficiary',
        'purpose',
        'lcy_amount',
        'issued_date',
        'expiary_date'  
    ];
}
