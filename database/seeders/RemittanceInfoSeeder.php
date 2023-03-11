<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemittanceInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post = [
            'parent_id' => null,
            'language_id' => 1,
            'title' => '',
            'slug' => '',
            'type' => \App\Helper\ConstantHelper::POST_TYPE_REMITTANCE_INFO,
            'description' => '',
            'is_active' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ];
        DB::table('posts')->insert($post);
    }
}
