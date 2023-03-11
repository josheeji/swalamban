<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seos = [
      [
        'seoable_id' => '',
        'seoable_type' => '',
        'page' => 'about_us',
        'meta_title' => 'about_us',
        'meta_keywords' => 'About Us',
        'meta_description' => 'About us description',
        'slug' => 'about_us', 'deletable' => '0'
      ],
    ];
    DB::table('seos')->insert($seos);
  }
}
