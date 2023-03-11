<?php

namespace Database\Seeders;

use App\Models\AdminAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $insert = [];
        $modules = DB::table('modules')->pluck('id')->toArray();
        $dbAdminAccess = AdminAccess::where('admin_type_id', 1)->pluck('module_id')->toArray();

        for ($i = 0; $i < count($modules); $i++) {
            if (!in_array($modules[$i], $dbAdminAccess)) {
                $insert[] = ['admin_type_id' => '1', 'module_id' => $modules[$i], 'view' => '1', 'add' => '1', 'edit' => '1', 'delete' => '1', 'changeStatus' => '1'];
            }
        }
        if (!empty($insert)) {
            DB::table('admin_accesses')->insert($insert);
        }
    }
}
