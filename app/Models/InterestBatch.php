<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestBatch extends Model
{
    protected $table = 'interest_batches';

    protected $fillable = [
        'title',
        'interest_date',
        'active',
    ];

    public function interestRates()
    {
        return $this->hasMany(InterestRate::class, 'batch');
    }
}
