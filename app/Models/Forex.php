<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forex extends Model
{
    protected $fillable = [
        'RTLIST_DATE',
        'FXD_CRNCY_CODE',
        'VAR_CRNCY_CODE',
        'FXD_CRNCY_UNITS',
        'BUY_RATE',
        'BUY_RATE_ABOVE',
        'SELL_RATE',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    // public function forexOrder()
    // {
    //     return $this->belongsTo(ForexOrder::class, 'FXD_CRNCY_CODE', 'code');
    // }
}
