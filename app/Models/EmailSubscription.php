<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelEventLogger;

class EmailSubscription extends Model
{
	use  ModelEventLogger, SoftDeletes;

	protected $table = 'email_subscriptions';

	protected $fillable = ['full_name', 'email', 'is_active'];
}