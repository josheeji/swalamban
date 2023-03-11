<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEnquiry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_type_id',
        'full_name',
        'email',
        'contact_no'
    ];

    public function product()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
