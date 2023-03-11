<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ModelEventLogger;

    protected  $table = 'stock_infos';
    protected $fillable = ['existing_record_id', 'language_id', 'paidup_value', 'maximum', 'minimum', 'closing', 'traded_share', 'is_active', 'created_by', 'updated_by', 'published_at'];

    public function existingRecord()
    {
        return $this->belongsTo(StockInfo::class, 'existing_record_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
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
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }
}
