<?php

namespace Database\Seeders;

use App\Models\BrokerCommission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            [
                'title' => 'SLAB 1',
                'range_from' => 0,
                'range_to' => 50000,
                'commission' => 0.6
            ],
            [
                'title' => 'SLAB 2',
                'range_from' => 50001,
                'range_to' => 500000,
                'commission' => 0.55
            ],
            [
                'title' => 'SLAB 3',
                'range_from' => 500001,
                'range_to' => 2000000,
                'commission' => 0.5
            ],
            [
                'title' => 'SLAB 4',
                'range_from' => 2000001,
                'range_to' => 10000000,
                'commission' => 0.45
            ],
            [
                'title' => 'SLAB 5',
                'range_from' => 10000001,
                'range_to' => 50000000000,
                'commission' => 0.4
            ]
        ];

        foreach ($rates as $rate) {
            if (!BrokerCommission::where('title', $rate['title'])->first()) {
                $rate['created_at'] = Carbon::now();
                DB::table('broker_commissions')->insert($rate);
            }
        }
    }
}
