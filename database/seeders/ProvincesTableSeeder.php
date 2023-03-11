<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            ['title' => 'Province 1', 'titleNP' => 'प्रदेश १'],
            ['title' => 'Province 2', 'titleNP' => 'प्रदेश २'],
            ['title' => 'Bagmati Province', 'titleNP' => 'बागमती प्रदेश'],
            ['title' => 'Gandaki Province', 'titleNP' => 'गण्डकी प्रदेश'],
            ['title' => 'Lumbini Province', 'titleNP' => 'लुम्बिनी प्रदेश'],
            ['title' => 'Karnali Province', 'titleNP' => 'कर्नाली प्रदेश'],
            ['title' => 'Sudurpashchim Province', 'titleNP' => 'सुदूर पश्चिम प्रदेश'],
        ];

        $dbProvinces = Province::pluck('title')->toArray();
        foreach ($provinces as $province) {
            if (!in_array($province['title'], $dbProvinces)) {
                unset($province['titleNP']);
                DB::table('provinces')->insert($province);
            }
        }
        foreach ($provinces as $province) {
            if ($pro = Province::where('title', $province['title'])->first()) {
                if (!Province::where('existing_record_id', $pro->id)->where('title', $province['titleNP'])->first()) {
                    $npContent['title'] = $province['titleNP'];
                    $npContent['existing_record_id'] = $pro->id;
                    $npContent['language_id'] = 2;
                    DB::table('provinces')->insert($npContent);
                }
            }
        }
    }
}
