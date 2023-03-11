<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['id' => 1, 'existing_record_id'=>null,'title' => 'News', 'slug' => 'news','language_id' => 1,'created_by' => 1],
            ['id' => 2, 'existing_record_id'=>1,'title' => 'News', 'slug' => 'news','language_id' => 2,'created_by' => 1],
            ['id' => 3, 'existing_record_id'=>null,'title' => 'Events', 'slug' => 'events','language_id' => 1,'created_by' => 1],
            ['id' => 4, 'existing_record_id'=>3,'title' => 'Events', 'slug' => 'events','language_id' => 2,'created_by' => 1],
            ['id' => 5, 'existing_record_id'=>null,'title' => 'CSR', 'slug' => 'csr','language_id' => 1,'created_by' => 1],
            ['id' => 6, 'existing_record_id'=>5,'title' => 'CSR', 'slug' => 'csr','language_id' => 2,'created_by' => 1],
        ];

        foreach ($categories as $data) {
            NewsCategory::updateOrcreate($data);
        }
    }
}
