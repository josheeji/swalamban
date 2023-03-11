<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = [
            ['name' => 'Surya Jyoti Life'],
            ['name' => 'Surya Life'],
            ['name' => 'Jyoti Life'],
        ];

        DB::table('companies')->insert($company);
    }
}