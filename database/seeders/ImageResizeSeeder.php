<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageResizeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $image_resize = [
      ['title' => 'Banner', 'slug' => 'banner', 'alias' => 'banner',  'image_resize_width' => 1349, 'image_resize_height' => 632],
      ['title' => 'Packagebanner', 'slug' => 'packagebanner', 'alias' => 'packagebanner',  'image_resize_width' => 1349, 'image_resize_height' => 632],
      ['title' => 'Destination', 'slug' => 'destination', 'alias' => 'destination',  'image_resize_width' => 800, 'image_resize_height' => 600],
      ['title' => 'Gallery', 'slug' => 'gallery', 'alias' => 'gallery',  'image_resize_width' => 800, 'image_resize_height' => 600],
      ['title' => 'Packages', 'slug' => 'packages', 'alias' => 'packages',  'image_resize_width' => 800, 'image_resize_height' => 600],
      ['title' => 'Activity', 'slug' => 'activity', 'alias' => 'activity',  'image_resize_width' => 44, 'image_resize_height' => 44],
      ['title' => 'Article', 'slug' => 'article', 'alias' => 'article',  'image_resize_width' => 800, 'image_resize_height' => 600],
      ['title' => 'Contents', 'slug' => 'contents', 'alias' => 'contents',  'image_resize_width' => 300, 'image_resize_height' => 400],
      ['title' => 'Testimonials', 'slug' => 'testimonials', 'alias' => 'testimonials',  'image_resize_width' => 121, 'image_resize_height' => 121],
      ['title' => 'Download', 'slug' => 'download', 'alias' => 'download',  'image_resize_width' => 300, 'image_resize_height' => 400],
      ['title' => 'Itinerary', 'slug' => 'itinerary', 'alias' => 'itinerary',  'image_resize_width' => 800, 'image_resize_height' => 600],
      ['title' => 'Package Route', 'slug' => 'packageroute', 'alias' => 'packageroute',  'image_resize_width' => 700, 'image_resize_height' => 740],
    ];

    DB::table('image_resize')->insert($image_resize);
  }
}
