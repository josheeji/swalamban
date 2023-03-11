<?php

namespace Database\Seeders;

use App\Models\AdminType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminTypes = [
            ['id' => 1, 'name' => 'Super Admin', 'is_active' => 1],
            ['id' => 2, 'name' => 'Site Admin', 'is_active' => 1],
        ];

        $dbAdminTypes = AdminType::pluck('name')->toArray();
        foreach ($adminTypes as $adminType) {
            if (!in_array($adminType['name'], $dbAdminTypes)) {
                $adminType['created_at'] = Carbon::now();
                DB::table('admin_types')->insert($adminType);
            }
        }
    }
}
