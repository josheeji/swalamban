<?php

namespace Database\Seeders;

use App\Models\Layout;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layouts = [
            ['title' => 'Default', 'slug' => 'default', 'image' => '', 'created_by' => DB::table('admins')->first()->id],
            // ['title' => 'Personal', 'slug' => 'personal', 'image' => '', 'created_by' => DB::table('admins')->first()->id],
            // ['title' => 'Business', 'slug' => 'business', 'image' => '', 'created_by' => DB::table('admins')->first()->id]
        ];

        $dbLayouts = Layout::pluck('slug')->toArray();
        foreach ($layouts as $layout) {
            if (!in_array($layout['slug'], $dbLayouts)) {
                DB::table('layouts')->insert($layout);
            }
        }
    }
}
