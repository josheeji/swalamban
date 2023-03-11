<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = [
            ['title' => 'Trekking', 'slug' => 'trekking', 'display_order' => 0, 'is_active' => 1],
            ['title' => 'Rafting', 'slug' => 'rafting', 'display_order' => 1, 'is_active' => 1],
        ];
        DB::table('articles')->insert($articles);


        $seo = [
            ['seoable_id' => 1, 'seoable_type' => 'App\Models\Article', 'page' => 'Article'],
            ['seoable_id' => 2, 'seoable_type' => 'App\Models\Article', 'page' => 'Article'],
        ];
        DB::table('seos')->insert($seo);
    }
}
