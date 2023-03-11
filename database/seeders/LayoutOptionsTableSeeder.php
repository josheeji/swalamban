<?php

namespace Database\Seeders;

use App\Models\LayoutOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layouts = DB::table('layouts')->pluck('id')->toArray();
        for ($i = 0; $i < count($layouts); $i++) {
            $options = [
                [
                    'title' => 'Header Top Menu',
                    'slug' => 'header-top-menu',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Primary Menu',
                    'slug' => 'primary-menu',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
//                [
//                    'title' => 'Banner Menu',
//                    'slug' => 'banner-menu',
//                    'excerpt' => 'Select the menu to show on banner.',
//                    'type' => '1',
//                    'value' => '',
//                    'layout_id' => $layouts[$i],
//                    'created_by' => DB::table('admins')->first()->id
//                ],
//                [
//                    'title' => 'Aside Menu',
//                    'slug' => 'aside-menu',
//                    'excerpt' => 'Select the menu to show on side navigation.',
//                    'type' => 1,
//                    'value' => '',
//                    'layout_id' => $layouts[$i],
//                    'created_by' => DB::table('admins')->first()->id
//                ],
//                [
//                    'title' => 'Calculator Menu',
//                    'slug' => 'calculator-menu',
//                    'excerpt' => 'Select the menu to show on calculator navigation.',
//                    'type' => 1,
//                    'value' => '',
//                    'layout_id' => $layouts[$i],
//                    'created_by' => DB::table('admins')->first()->id
//                ],
//                [
//                    'title' => 'Open An Account',
//                    'slug' => 'open-an-account',
//                    'excerpt' => '',
//                    'type' => '1',
//                    'value' => '',
//                    'layout_id' => $layouts[$i],
//                    'created_by' => DB::table('admins')->first()->id
//                ],
                [
                    'title' => 'Block 1',
                    'slug' => 'block-1',
                    'excerpt' => '',
                    'type' => '2',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Block 2',
                    'slug' => 'block-2',
                    'excerpt' => '',
                    'type' => '2',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Block 3',
                    'slug' => 'block-3',
                    'excerpt' => '',
                    'type' => '2',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Block 4',
                    'slug' => 'block-4',
                    'excerpt' => '',
                    'type' => '2',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Widget 1',
                    'slug' => 'widget-1',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Widget 2',
                    'slug' => 'widget-2',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Widget 3',
                    'slug' => 'widget-3',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Widget 4',
                    'slug' => 'widget-4',
                    'excerpt' => '',
                    'type' => '1',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                // [
                //     'title' => 'Widget 5',
                //     'slug' => 'widget-5',
                //     'excerpt' => '',
                //     'type' => '1',
                //     'value' => '',
                //     'layout_id' => 1,
                //     'created_by' => DB::table('admins')->first()->id
                // ],
                // [
                //     'title' => 'Widget 6',
                //     'slug' => 'widget-6',
                //     'excerpt' => '',
                //     'type' => '1',
                //     'value' => '',
                //     'layout_id' => 1,
                //     'created_by' => DB::table('admins')->first()->id
                // ],
                // [
                //     'title' => 'Widget 7',
                //     'slug' => 'widget-7',
                //     'excerpt' => '',
                //     'type' => '1',
                //     'value' => '',
                //     'layout_id' => 1,
                //     'created_by' => DB::table('admins')->first()->id
                // ],
                // [
                //     'title' => 'Widget 8',
                //     'slug' => 'widget-8',
                //     'excerpt' => '',
                //     'type' => '1',
                //     'value' => '',
                //     'layout_id' => 1,
                //     'created_by' => DB::table('admins')->first()->id
                // ]
                [
                    'title' => 'Top Block 1',
                    'slug' => 'top-block-1',
                    'excerpt' => '',
                    'type' => '3',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Top Block 2',
                    'slug' => 'top-block-2',
                    'excerpt' => '',
                    'type' => '3',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Top Block 3',
                    'slug' => 'top-block-3',
                    'excerpt' => '',
                    'type' => '3',
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Search Menu Top',
                    'slug' => 'search-menu-1',
                    'excerpt' => 'Select the menu to show on search block.',
                    'type' => 1,
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
                [
                    'title' => 'Search Menu Bottom',
                    'slug' => 'search-menu-2',
                    'excerpt' => 'Select the menu to show on search block.',
                    'type' => 1,
                    'value' => '',
                    'layout_id' => $layouts[$i],
                    'created_by' => DB::table('admins')->first()->id
                ],
            ];

            $dbOptions = LayoutOption::pluck('slug')->toArray();
            foreach ($options as $option) {
                if (!in_array($option['slug'], $dbOptions)) {
                    DB::table('layout_options')->insert($option);
                }
            }
        }
    }
}
