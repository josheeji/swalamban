<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->truncate();
        DB::table('menu_items')->truncate();

        $menu = [];



        $sql = "INSERT INTO `menu` (`id`, `language_id`, `existing_record_id`, `title`, `slug`, `location_main`, `location_footer`, `location_aside`, `display_order`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Top Menu', 'top-menu', 1, 0, 0, 0, 1, 1, NULL, '2019-12-03 18:05:57', '2019-12-03 18:05:57'),
(2, 2, 1, 'Top Menu - NP', '', 1, 0, 0, 0, 1, NULL, NULL, NULL, NULL),
(3, 1, NULL, 'Primary Menu', 'primary-menu', 0, 0, 0, 0, 1, 1, NULL, '2019-12-31 14:44:39', '2019-12-31 14:44:39'),
(4, 1, NULL, 'Useful Links', 'useful-links', 0, 0, 0, 0, 1, 1, NULL, '2019-12-31 14:44:54', '2019-12-31 14:44:54'),
(5, 1, NULL, 'Features', 'features', 0, 0, 0, 0, 1, 1, NULL, '2019-12-31 14:45:02', '2019-12-31 14:45:02'),
(6, 1, NULL, 'Services', 'services', 0, 0, 0, 0, 1, 1, 1, '2019-12-31 14:45:11', '2019-12-31 14:45:28'),
(7, 1, NULL, 'Information', 'information', 0, 0, 0, 0, 1, 1, NULL, '2019-12-31 14:45:37', '2019-12-31 14:45:37'),
(8, 1, NULL, 'Are you looking for?', 'are-you-looking-for', 0, 0, 0, 0, 1, 1, 1, '2019-12-31 14:46:25', '2019-12-31 14:46:35')";
    }
}
