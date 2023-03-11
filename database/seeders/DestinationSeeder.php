<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $destination = [
            ['name' => 'Nepal', 'slug' => 'nepal', 'is_active' => 1],
            ['name' => 'Bhutan', 'slug' => 'bhutan', 'is_active' => 1],
            ['name' => 'Multi Country', 'slug' => 'multi-country', 'is_active' => 1],
        ];
        DB::table('destinations')->insert($destination);

        $seo = [
            ['seoable_id' => 1, 'seoable_type' => 'App\Models\Destination', 'page' => 'Destination'],
            ['seoable_id' => 2, 'seoable_type' => 'App\Models\Destination', 'page' => 'Destination'],
            ['seoable_id' => 3, 'seoable_type' => 'App\Models\Destination', 'page' => 'Destination'],
        ];
        DB::table('seos')->insert($seo);
    }
}
