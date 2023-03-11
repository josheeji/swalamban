<?php

namespace Database\Seeders;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            ['admin_type_id' => 1, 'first_name' => 'Superadmin', 'email' => 'superadmin@pndc.com', 'password' => bcrypt('password'), 'is_active' => 1],
        ];
        $dbAdmins = Admin::pluck('email')->toArray();
        foreach ($admins as $admin) {
            if (!in_array($admin['email'], $dbAdmins)) {
                $admin['created_at'] = Carbon::now();
                DB::table('admins')->insert($admin);
            }
        }
    }
}
