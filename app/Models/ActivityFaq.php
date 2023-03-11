<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityFaq extends Model
{
    use ModelEventLogger;

    protected $fillable = ['faq_activity_id', 'question', 'answer', 'is_active'];

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity', 'faq_activity_id');
    }
}
