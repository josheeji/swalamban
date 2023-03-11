<?php

namespace Database\Seeders;

use App\Models\BrokerCommissionType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerCommissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'type' => 'Individual',
                'commission' => 0.05
            ],
            [
                'type' => 'Institution',
                'commission' => 0.10
            ]
        ];

        foreach ($types as $type) {
            if (!BrokerCommissionType::where('type', $type['type'])->first()) {
                $type['created_at'] = Carbon::now();
                DB::table('broker_commission_types')->insert($type);
            }
        }
    }
}
