<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $activities = [
            ['title' => 'Trekking and Hiking', 'slug' => 'trekking-hiking', 'is_active' => 1],
            ['title' => 'Rafting', 'slug' => 'rafting', 'is_active' => 1],
            ['title' => 'Climbing and Expedition', 'slug' => 'climbing-expedition', 'is_active' => 1],
            ['title' => 'Day Tours', 'slug' => 'day-tours', 'is_active' => 1],
            ['title' => 'Cycling and Mountain Biking', 'slug' => 'cycling-mountain-biking', 'is_active' => 1],
            ['title' => 'Moto Cycling Tours', 'slug' => 'moto-cycling-tours', 'is_active' => 1],
            ['title' => 'Heli Tours', 'slug' => 'heli-tours', 'is_active' => 1],
            ['title' => 'Cultural and Holiday Tours', 'slug' => 'culture-holiday-tour', 'is_active' => 1],
        ];
        DB::table('activities')->insert($activities);


        $seo = [
            ['seoable_id' => 1, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 2, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 3, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 4, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 5, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 6, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 7, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
            ['seoable_id' => 8, 'seoable_type' => 'App\Models\Activity', 'page' => 'Activity'],
        ];
        DB::table('seos')->insert($seo);
    }
}
