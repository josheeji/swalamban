<?php

namespace App\Models;

use App\Traits\ModelEventLogger;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use Sluggable, ModelEventLogger,  SoftDeletes;

     public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = ['activity_id','destination_id','display_order', 'title', 'slug', 'image', 'duration', 'cover_image', 'description', 'cost', 'tac',  'trip_overview', 'itinerary_details', 'includes_excludes', 'food', 'difficulty', 'accommodation', 'start_end', 'trip_code', 'max_altitude', 'transportation', 'best_season', 'is_active', 'created_by', 'updated_by', 'status_by', 'deleted_by','route_image','route_map','best_sale', 'category_id'];

//

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function images(){
        return $this->hasMany('App\Models\TravelPackageImage', 'package_id');
    }

    public function dates(){
        return $this->hasMany('App\Models\TravelPackageDate', 'package_id');
    }

    public function itinerary(){
        return $this->hasMany('App\Models\Itinerary', 'package_id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updator(){
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

     public function seo()
    {
        return $this->morphOne('App\Models\Seo', 'seoable');
    }

    public function activities(){
        return $this->belongsTo('App\Models\Activity', 'activity_id');
    }

    public function destinations(){
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\PackageCategory', 'category_id');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('M d') : "";
    }

    public function getMonthCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('M') : "";
    }

    public function getDayCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('d') : "";
    }
   
}
