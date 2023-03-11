<?php

namespace Database\Seeders;

use App\Models\ForexOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForexOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $modules = [
            ['name' => 'Indian Rupee', 'code' => 'INR', 'unit' => '100', 'order' => '1'],
            ['name' => 'U.S. Dollar', 'code' => 'USD', 'unit' => '1', 'order' => '2'],
            ['name' => 'European Euro', 'code' => 'EUR', 'unit' => '1', 'order' => '3'],
            ['name' => 'UK Pound Sterling', 'code' => 'GBP', 'unit' => '1', 'order' => '4'],

            ['name' => 'Swiss Franc', 'code' => 'CHF', 'unit' => '1', 'order' => '5'],
            ['name' => 'Australian Dollar', 'code' => 'AUD', 'unit' => '1', 'order' => '6'],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'unit' => '1', 'order' => '7'],
            ['name' => 'Singapore Dollar', 'code' => 'SGD', 'unit' => '1', 'order' => '8'],

            ['name' => 'Japanese Yen', 'code' => 'JPY', 'unit' => '10', 'order' => '9'],
            ['name' => 'Chinese Yuan', 'code' => 'CNY', 'unit' => '1', 'order' => '10'],
            ['name' => 'Saudi Arabian Riyal', 'code' => 'SAR', 'unit' => '1', 'order' => '11'],
            ['name' => 'Qatari Riyal', 'code' => 'QAR', 'unit' => '1', 'order' => '12'],

            ['name' => 'Thai Baht', 'code' => 'THB', 'unit' => '1', 'order' => '13'],
            ['name' => 'UAE Dirham', 'code' => 'AED', 'unit' => '1', 'order' => '14'],
            ['name' => 'Malaysian Ringgit', 'code' => 'MYR', 'unit' => '1', 'order' => '15'],
            ['name' => 'South Korean Won', 'code' => 'KRW', 'unit' => '1', 'order' => '16'],

            ['name' => 'Swedish Kroner', 'code' => 'SEK', 'unit' => '1', 'order' => '17'],
            ['name' => 'Danish Kroner', 'code' => 'DKK', 'unit' => '1', 'order' => '18'],
            ['name' => 'Hong Kong Dollar', 'code' => 'HKD', 'unit' => '1', 'order' => '19'],
            ['name' => 'Kuwaity Dinar', 'code' => 'KWD', 'unit' => '1', 'order' => '20'],

            ['name' => 'Bahrain Dinar', 'code' => 'BHD', 'unit' => '1', 'order' => '21'],
        ];

        $forexOrders = ForexOrder::pluck('code', 'id')->toArray();
        foreach ($modules as $module) {
            if (in_array($module['code'], $forexOrders)) {
                DB::table('forex_orders')->where('code', $module['code'])->update($module);
            } else {
                DB::table('forex_orders')->insert($module);
            }
        }
    }
}
