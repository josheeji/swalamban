<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['language_id' => 1, 'title' => 'Top Menu', 'slug' => 'top-menu'],
            ['language_id' => 1, 'title' => 'Primary Menu', 'slug' => 'primary-menu'],
            ['language_id' => 1, 'title' => 'Useful Links', 'slug' => 'useful-links'],
            ['language_id' => 1, 'title' => 'Features', 'slug' => 'features'],
            ['language_id' => 1, 'title' => 'Services', 'slug' => 'services'],
            ['language_id' => 1, 'title' => 'Information', 'slug' => 'information'],
            ['language_id' => 1, 'title' => 'Are you looking for?', 'slug' => 'are-you-looking-for']
        ];
        DB::table('menu')->insert($menus);
    }
}
