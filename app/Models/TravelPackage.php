<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    use Sluggable, ModelEventLogger;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = ['display_order', 'title', 'slug', 'image', 'duration', 'cover_image', 'description', 'cost', 'tac',  'trip_overview', 'itinerary_details', 'includes_excludes', 'food', 'difficulty', 'accommodation', 'start_end', 'group_size', 'max_altitude', 'transportation', 'best_season', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function images(){
        return $this->hasMany('App\Models\TravelPackageImage', 'travel_package_id');
    }

    public function dates(){
        return $this->hasMany('App\Models\TravelPackageDate', 'travel_package_id');
    }

    public function itinerary(){
        return $this->hasMany('App\Models\TravelPackageItinerary', 'travel_package_id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updator(){
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
