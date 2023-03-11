<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterestRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'batch',
        'type',
        'excerpt',
        'content',
        'display_order',
        'updated_by',
        'created_by',
        'deleted_by',
        'is_active',
        'date',
    ];

    public function interestBatch()
    {
        return $this->belongsTo(InterestBatch::class, 'batch');
    }
}
