<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTypeTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        // $this->call(ArticleSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(SiteSettingSeeder::class);
        $this->call(AdminAccessSeeder::class);
        // $this->call(ImageResizeSeeder::class);
        // $this->call(DestinationSeeder::class);
        // $this->call(ActivitySeeder::class);
        // $this->call(ContentSeeder::class);
        // $this->call(BannerSeeder::class);
        // $this->call(TestimonialSeeder::class);
        // $this->call(PackageSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(LayoutsTableSeeder::class);
        $this->call(LayoutOptionsTableSeeder::class);
        $this->call(CompanySeeder::class);
        // $this->call(RemittanceInfoSeeder::class);
        // $this->call(MenuTableSeeder::class);
        // $this->call(BrokerCommissionSeeder::class);
        // $this->call(BrokerCommissionTypeSeeder::class);
    }
}