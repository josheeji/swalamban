<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'About Us',
                'slug' => 'about-us',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Personal Banking',
                'slug' => 'personal-banking',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Business Banking',
                'slug' => 'business-banking',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Trade Finance',
                'slug' => 'trade-finance',
                'description' => 'Description not added yet',
                'is_active' => 1
            ],
            [
                'parent_id' => null,
                'language_id' => 1,
                'title' => 'Remittance',
                'slug' => 'remittance',
                'description' => 'Description not added yet',
                'is_active' => 1
            ]
        ];
        DB::table('contents')->insert($contents);
    }
}
