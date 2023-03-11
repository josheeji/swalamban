<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package = [

            ['destination_id' => '1', 'activity_id' => '1', 'title' => 'Everest Base Camp', 'description' => 'A trek designed to get you real close-up to the ‘mother of all peaks’ Mt. Everest, the highest peak in the world. Mount Everest Base Camp has been a popular destination for trekkers since the very first expeditions to the Nepalese side of Everest in the 1953. To reach this part of the Himalaya within a limited time, we have designed this two weeks trek to Everest Base Camp which runs directly to and from Lukla (The Gateway to Everest). All along this part of the trail, villages are interspersed with magnificent forests of rhododendron, magnolia and giant firs. In both the early autumn and late spring, the flowers on this portion of the trek make it the kind of walk you will remember for a long, long time.', 'slug' => 'everest-base-camp', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus Air', 'best_season' => 'March - June', 'max_altitude' => '1500 m', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Pokhara', 'trip_code' => 'MATREK07', 'cost' => '1234', 'is_active' => 1],
            ['destination_id' => '2', 'activity_id' => '1', 'title' => 'Omba Nye Eastern Trek', 'description' => 'Bhutan Himalayas are trekking lovers paradise! Most treks are organised expedition style with tents, support staff and horses to carry luggage. We provide comfortable service, so you don\'t need to worry and just enjoy the stunning mountain landscape! For slightly easier programs with accommodation in hotels and local farmhouses', 'slug' => 'omba-nye-eastern-trek', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus Air', 'best_season' => 'March - June', 'max_altitude' => '1500 m', 'accommodation' => '3 Star Hotel', 'start_end' => 'Thimbu- Bumdrak', 'trip_code' => 'MATREK07', 'cost' => '1234', 'is_active' => 1],

            ['destination_id' => '1', 'activity_id' => '2', 'title' => 'Trisuli River Rafting/Kayaking', 'description' => 'The scenery of river Trishuli includes small gorges and a glimpse of the cable car leading to the famous Hindu Temple Manakamana.For the most of the year the rapids encountered on the Trisuli are straightforward, easily negotiated and well spaced out. Trisuli river is an excellent river for those looking for a short river trip, without the challenge of huge rapids, but with some really exciting rapids, with beautiful scenery and a relatively peaceful environment. During the monsoon months the intensity of the rapids increases and attracts a radically different set of rafters. But there are sections for rafting during the monsoon for those who are looking for simply exciting trip!', 'slug' => 'trisuli-river-rafting-kayaking', 'duration' => '2', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus', 'best_season' => 'March - June', 'max_altitude' => '', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Kurintar', 'trip_code' => 'MATREK08', 'cost' => '1000', 'is_active' => 1],
            ['destination_id' => '1', 'activity_id' => '3', 'title' => 'Everest Submit Climbing', 'description' => 'Mount Everest, known in Nepali as Sagarmatha, in Tibetan as Chomolungma and in Chinese as Zhumulangma, is Earth\'s highest mountain above sea level, located in the Mahalangur Himal sub-range of the Himalayas. The international border between Nepal and China runs across its summit point', 'slug' => 'everest-submit-climb', 'duration' => '2', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus', 'best_season' => 'March - June', 'max_altitude' => '', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Kurintar', 'trip_code' => 'MATREK08', 'cost' => '1000', 'is_active' => 1],
            [
                'destination_id' => '1', 'activity_id' => '4', 'title' => 'Pashupatinath  & Boudhanath & Patan', 'description' => '
            Day tours in Nepal, Nepal is one of the best countries in the world for a cultural and historical sightseeing day tour with its long history, fascinating art and distinctive and varied architecture,Nepalese people are renowned for the welcome and hospitality they offer to visitors and it is often this experience that people remember more than any other. This half day and full day tour provide its visitors an opportunity to observe many rich Nepalese cultural traditions, a unique cultural world, and history. ',
                'slug' => 'pashupatinath-boudhanath-patan', 'duration' => '2', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus', 'best_season' => 'Jan - Dec', 'max_altitude' => '', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu', 'trip_code' => 'MATREK09', 'cost' => '500', 'is_active' => 1
            ],
            ['destination_id' => '1', 'activity_id' => '5', 'title' => 'Mountain Bike Upper Mustang', 'description' => 'Mountain Biking in Mustang Valley', 'slug' => 'mountain-bike-upper-mustang', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus-Air', 'best_season' => 'March - June', 'max_altitude' => '3400', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Mustang', 'trip_code' => 'MATREK08', 'cost' => '1000', 'is_active' => 1],
            ['destination_id' => '1', 'activity_id' => '6', 'title' => 'Moto Cycling Tour', 'description' => 'Moto Cycling Tour', 'slug' => 'moto-cycling-tour', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus-Air', 'best_season' => 'March - June', 'max_altitude' => '3400', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Mustang', 'trip_code' => 'MATREK18', 'cost' => '1000', 'is_active' => 1],
            ['destination_id' => '1', 'activity_id' => '7', 'title' => 'Heli Tour', 'description' => 'Heli Tour all over Nepal', 'slug' => 'heli-tour', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus-Air', 'best_season' => 'March - June', 'max_altitude' => '3400', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Mustang', 'trip_code' => 'MATREK18', 'cost' => '1000', 'is_active' => 1],

            ['destination_id' => '1', 'activity_id' => '8', 'title' => 'Pashupatinath Muktinath Darshan', 'description' => 'Pashupatinath Muktinath Darshan', 'slug' => 'pashupatinath-muktinath-darshan', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus-Air', 'best_season' => 'March - June', 'max_altitude' => '3100', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Mustang', 'trip_code' => 'MATREK28', 'cost' => '1000', 'is_active' => 1],
            ['destination_id' => '2', 'activity_id' => '8', 'title' => 'Bhutan Cultural Tour', 'description' => 'Bhutan Cultural Tour', 'slug' => 'bhutan-cultural-tour', 'duration' => '12', 'includes_excludes' => 'not included yet', 'transportation' => 'Bus-Air', 'best_season' => 'Jan - Dec', 'max_altitude' => '3100', 'accommodation' => '3 Star Hotel', 'start_end' => 'Kathmandu - Mustang', 'trip_code' => 'MATREK28', 'cost' => '1000', 'is_active' => 1],

        ];
        DB::table('packages')->insert($package);



        $seo = [
            ['seoable_id' => 1, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 2, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 3, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 4, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 5, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 6, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 7, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 8, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 9, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
            ['seoable_id' => 10, 'seoable_type' => 'App\Models\Package', 'page' => 'Package'],
        ];
        DB::table('seos')->insert($seo);
    }
}
