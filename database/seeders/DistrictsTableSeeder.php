<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            ['province_id' => 1, 'title' => 'Ilam', 'titleNP' => 'इलाम', 'headquarter' => 'Ilam', 'latitude' => 26.90, 'longitude' => 87.93],
            ['province_id' => 1, 'title' => 'Jhapa', 'titleNP' => 'झापा', 'headquarter' => 'Chandragadhi', 'latitude' => 26.64, 'longitude' => 87.89],
            ['province_id' => 1, 'title' => 'Panchthar', 'titleNP' => 'पाँचथर', 'headquarter' => 'Phidim', 'latitude' => 27.20, 'longitude' => 87.82],
            ['province_id' => 1, 'title' => 'Taplejung', 'titleNP' => 'ताप्लेजुङ', 'headquarter' => 'Taplejung', 'latitude' => 27.35, 'longitude' => 87.67],
            ['province_id' => 5, 'title' => 'Dang Deukhuri', 'titleNP' => 'दाङ देउकुरी', 'headquarter' => 'Ghorahi', 'latitude' => 28.00, 'longitude' => 82.27],
            ['province_id' => 5, 'title' => 'Pyuthan', 'titleNP' => 'प्युठान', 'headquarter' => 'Pyuthan Khalanga', 'latitude' => 28.10, 'longitude' => 82.87],
            ['province_id' => 5, 'title' => 'Rolpa', 'titleNP' => 'रोल्पा', 'headquarter' => 'Liwang', 'latitude' => 28.38, 'longitude' => 82.65],
            ['province_id' => 5, 'title' => 'Eastern Rukum District	', 'titleNP' => 'पूर्वी रुकुम जिल्ला', 'headquarter' => 'Rukumkot', 'latitude' => 28.74, 'longitude' => 82.48],
            ['province_id' => 6, 'title' => 'Salyan', 'titleNP' => 'सल्यान', 'headquarter' => 'Salyan Khalanga', 'latitude' => 28.28, 'longitude' => 83.79],
            ['province_id' => 3, 'title' => 'Bhaktapur', 'titleNP' => 'भक्तपुर', 'headquarter' => 'Bhaktapur', 'latitude' => 27.67, 'longitude' => 85.43],
            ['province_id' => 3, 'title' => 'Dhading', 'titleNP' => 'धादिङ', 'headquarter' => 'Dhading Besi', 'latitude' => 27.87, 'longitude' => 84.92],
            ['province_id' => 3, 'title' => 'Kathmandu', 'titleNP' => 'काठमाडौं', 'headquarter' => 'Kathmandu', 'latitude' => 27.70, 'longitude' => 85.32],
            ['province_id' => 3, 'title' => 'Kavrepalanchok', 'titleNP' => 'काभ्रेपालानचोक', 'headquarter' => 'Dhulikhel', 'latitude' => 27.53, 'longitude' => 85.56],
            ['province_id' => 3, 'title' => 'Lalitpur', 'titleNP' => 'ललितपुर', 'headquarter' => 'Patan', 'latitude' => 27.54, 'longitude' => 85.33],
            ['province_id' => 3, 'title' => 'Nuwakot', 'titleNP' => 'नुवाकोट', 'headquarter' => 'Bidur', 'latitude' => 27.97, 'longitude' => 83.06],
            ['province_id' => 3, 'title' => 'Rasuwa', 'titleNP' => 'रसुवा', 'headquarter' => 'Dhunche', 'latitude' => 27.08, 'longitude' => 86.43],
            ['province_id' => 3, 'title' => 'Sindhupalchok', 'titleNP' => 'सिन्धुपाल्चोक', 'headquarter' => 'Chautara', 'latitude' => 27.95, 'longitude' => 85.68],
            ['province_id' => 6, 'title' => 'Dolpa', 'titleNP' => 'डोल्पा', 'headquarter' => 'Dolpa', 'latitude' => 29.00, 'longitude' => 82.82],
            ['province_id' => 6, 'title' => 'Humla', 'titleNP' => 'हुम्ला', 'headquarter' => 'Simikot', 'latitude' => 29.97, 'longitude' => 81.83],
            ['province_id' => 6, 'title' => 'Jumla', 'titleNP' => 'जुमला', 'headquarter' => 'Jumla Khalanga', 'latitude' => 29.28, 'longitude' => 82.18],
            ['province_id' => 6, 'title' => 'Kalikot', 'titleNP' => 'कालीकोट', 'headquarter' => 'Kalikot', 'latitude' => 29.15, 'longitude' => 81.62],
            ['province_id' => 6, 'title' => 'Mugu', 'titleNP' => 'मुगु', 'headquarter' => 'Gamgadhi', 'latitude' => 29.87, 'longitude' => 82.62],
            ['province_id' => 1, 'title' => 'Khotang', 'titleNP' => 'खोटाङ', 'headquarter' => 'Diktel', 'latitude' => 27.23, 'longitude' => 86.82],
            ['province_id' => 1, 'title' => 'Okhaldhunga', 'titleNP' => 'ओखलढुङ्गा', 'headquarter' => 'Okhaldhunga', 'latitude' => 27.32, 'longitude' => 86.50],
            ['province_id' => 2, 'title' => 'Saptari', 'titleNP' => 'सप्तरी', 'headquarter' => 'Rajbiraj', 'latitude' => 26.62, 'longitude' => 86.70],
            ['province_id' => 2, 'title' => 'Siraha', 'titleNP' => 'सिराहा', 'headquarter' => 'Siraha', 'latitude' => 26.66, 'longitude' => 86.21],
            ['province_id' => 1, 'title' => 'Solukhumbu', 'titleNP' => 'सोलुखुम्बु', 'headquarter' => 'Salleri', 'latitude' => 27.79, 'longitude' => 86.66],
            ['province_id' => 1, 'title' => 'Udayapur', 'titleNP' => 'उदयपुर', 'headquarter' => 'Gaighat', 'latitude' => 27.57, 'longitude' => 82.90],
            ['province_id' => 1, 'title' => 'Bhojpur', 'titleNP' => 'भोजपुर', 'headquarter' => 'Bhojpur', 'latitude' => 27.17, 'longitude' => 87.05],
            ['province_id' => 1, 'title' => 'Dhankuta', 'titleNP' => 'धनकुटा', 'headquarter' => 'Dhankuta', 'latitude' => 26.98, 'longitude' => 87.33],
            ['province_id' => 1, 'title' => 'Morang', 'titleNP' => 'मोरङ', 'headquarter' => 'Biratnagar', 'latitude' => 26.68, 'longitude' => 87.46],
            ['province_id' => 1, 'title' => 'Sankhuwasabha', 'titleNP' => 'संखुवासभा', 'headquarter' => 'Khandbari', 'latitude' => 27.61, 'longitude' => 87.14],
            ['province_id' => 1, 'title' => 'Sunsari', 'titleNP' => 'सुनसरी', 'headquarter' => 'Inaruwa', 'latitude' => 26.63, 'longitude' => 87.18],
            ['province_id' => 1, 'title' => 'Terhathum', 'titleNP' => 'तेह्रथुम', 'headquarter' => 'Manglung', 'latitude' => 27.20, 'longitude' => 87.50],
            ['province_id' => 2, 'title' => 'Bara', 'titleNP' => 'बारा', 'headquarter' => 'Kalaiya', 'latitude' => 27.03, 'longitude' => 85.00],
            ['province_id' => 3, 'title' => 'Chitwan', 'titleNP' => 'चितवन', 'headquarter' => 'Bharatpur', 'latitude' => 27.53, 'longitude' => 84.35],
            ['province_id' => 3, 'title' => 'Makwanpur', 'titleNP' => 'मकवानपुर', 'headquarter' => 'Hetauda', 'latitude' => 27.37, 'longitude' => 85.19],
            ['province_id' => 2, 'title' => 'Parsa', 'titleNP' => 'पार्सा', 'headquarter' => 'Birgunj', 'latitude' => 26.88, 'longitude' => 85.63],
            ['province_id' => 2, 'title' => 'Rautahat', 'titleNP' => 'रौतहट', 'headquarter' => 'Gaur', 'latitude' => 26.57, 'longitude' => 86.53],
            ['province_id' => 7, 'title' => 'Baitadi', 'titleNP' => 'बैतडी', 'headquarter' => 'Baitadi', 'latitude' => 29.52, 'longitude' => 80.47],
            ['province_id' => 7, 'title' => 'Dadeldhura', 'titleNP' => 'डडेल्धुरा', 'headquarter' => 'Dadeldhura', 'latitude' => 29.30, 'longitude' => 80.59],
            ['province_id' => 7, 'title' => 'Darchula', 'titleNP' => 'दार्चुला', 'headquarter' => 'Darchula', 'latitude' => 29.83, 'longitude' => 80.55],
            ['province_id' => 7, 'title' => 'Kanchanpur', 'titleNP' => 'कंचनपुर', 'headquarter' => 'Mahendara Nagar', 'latitude' => 28.20, 'longitude' => 82.17],
            ['province_id' => 4, 'title' => 'Gorkha', 'titleNP' => 'गोरखा', 'headquarter' => 'Gorkha', 'latitude' => 28.00, 'longitude' => 84.63],
            ['province_id' => 4, 'title' => 'Kaski', 'titleNP' => 'कास्की', 'headquarter' => 'Pokhara', 'latitude' => 28.28, 'longitude' => 83.87],
            ['province_id' => 4, 'title' => 'Lamjung', 'titleNP' => 'लमजुङ', 'headquarter' => 'Bensi Sahar', 'latitude' => 28.28, 'longitude' => 84.35],
            ['province_id' => 4, 'title' => 'Manang', 'titleNP' => 'मनाङ', 'headquarter' => 'Chame', 'latitude' => 28.67, 'longitude' => 84.02],
            ['province_id' => 4, 'title' => 'Syangja', 'titleNP' => 'स्याङ्जा', 'headquarter' => 'Syangja', 'latitude' => 28.10, 'longitude' => 83.88],
            ['province_id' => 4, 'title' => 'Tanahu', 'titleNP' => 'तनहुँ', 'headquarter' => 'Damauli', 'latitude' => 27.94, 'longitude' => 84.23],
            ['province_id' => 2, 'title' => 'Dhanusa', 'titleNP' => 'धनुसा', 'headquarter' => 'Janakpur', 'latitude' => 26.84, 'longitude' => 86.01],
            ['province_id' => 3, 'title' => 'Dholkha', 'titleNP' => 'ढोलखा', 'headquarter' => 'Charikot', 'latitude' => 27.78, 'longitude' => 86.18],
            ['province_id' => 2, 'title' => 'Mahottari', 'titleNP' => 'महोत्तरी', 'headquarter' => 'Jaleswor', 'latitude' => 26.88, 'longitude' => 85.81],
            ['province_id' => 3, 'title' => 'Ramechhap', 'titleNP' => 'रामेछाप', 'headquarter' => 'Manthali', 'latitude' => 27.33, 'longitude' => 86.08],
            ['province_id' => 2, 'title' => 'Sarlahi', 'titleNP' => 'सरलाही', 'headquarter' => 'Malangwa', 'latitude' => 27.01, 'longitude' => 85.52],
            ['province_id' => 3, 'title' => 'Sindhuli', 'titleNP' => 'सिन्धुली', 'headquarter' => 'Sindhuli Madhi', 'latitude' => 27.26, 'longitude' => 85.97],
            ['province_id' => 5, 'title' => 'Arghakhanchi', 'titleNP' => 'अर्घाखाँची', 'headquarter' => 'Sandhikharka', 'latitude' => 28.00, 'longitude' => 83.25],
            ['province_id' => 5, 'title' => 'Gulmi', 'titleNP' => 'गुल्मी', 'headquarter' => 'Tamghas', 'latitude' => 28.09, 'longitude' => 83.29],
            ['province_id' => 5, 'title' => 'Kapilvastu', 'titleNP' => 'कपिलवस्तु', 'headquarter' => 'Taulihawa', 'latitude' => 27.54, 'longitude' => 83.05],
            ['province_id' => 4, 'title' => 'Nawalpur', 'titleNP' => 'नवलपुर', 'headquarter' => 'Kawasoti', 'latitude' => 27.65, 'longitude' => 83.89],
            ['province_id' => 5, 'title' => 'Palpa', 'titleNP' => 'पाल्पा', 'headquarter' => 'Tansen', 'latitude' => 27.87, 'longitude' => 83.55],
            ['province_id' => 5, 'title' => 'Rupandehi', 'titleNP' => 'रूपन्देही', 'headquarter' => 'Bhairahawa', 'latitude' => 27.63, 'longitude' => 83.38],
            ['province_id' => 7, 'title' => 'Achham', 'titleNP' => 'अछाम', 'headquarter' => 'Mangalsen', 'latitude' => 29.05, 'longitude' => 81.30],
            ['province_id' => 7, 'title' => 'Bajhang', 'titleNP' => 'बझाङ', 'headquarter' => 'Chainpur', 'latitude' => 29.55, 'longitude' => 81.20],
            ['province_id' => 7, 'title' => 'Bajura', 'titleNP' => 'बाजुरा', 'headquarter' => 'Martadi', 'latitude' => 29.45, 'longitude' => 81.49],
            ['province_id' => 7, 'title' => 'Doti', 'titleNP' => 'डोटी', 'headquarter' => 'Dipayal', 'latitude' => 29.27, 'longitude' => 80.98],
            ['province_id' => 7, 'title' => 'Kailali', 'titleNP' => 'कैलाली', 'headquarter' => 'Dhangadhi', 'latitude' => 28.68, 'longitude' => 80.60],
            ['province_id' => 5, 'title' => 'Banke', 'titleNP' => 'बाँके', 'headquarter' => 'Nepalgunj', 'latitude' => 28.05, 'longitude' => 81.62],
            ['province_id' => 5, 'title' => 'Bardiya', 'titleNP' => 'बर्दिया', 'headquarter' => 'Gulariya', 'latitude' => 28.82, 'longitude' => 80.48],
            ['province_id' => 6, 'title' => 'Dailekh', 'titleNP' => 'दैलेख', 'headquarter' => 'Dullu', 'latitude' => 28.84, 'longitude' => 81.71],
            ['province_id' => 6, 'title' => 'Jajarkot', 'titleNP' => 'जाजरकोट', 'headquarter' => 'Khalanga', 'latitude' => 28.73, 'longitude' => 82.22],
            ['province_id' => 6, 'title' => 'Surkhet', 'titleNP' => 'सुर्खेत', 'headquarter' => 'Surkhet', 'latitude' => 28.60, 'longitude' => 81.63],
            ['province_id' => 4, 'title' => 'Baglung', 'titleNP' => 'बाग्लुङ', 'headquarter' => 'Baglung', 'latitude' => 28.27, 'longitude' => 83.60],
            ['province_id' => 4, 'title' => 'Mustang', 'titleNP' => 'मुस्ताङ', 'headquarter' => 'Jomsom', 'latitude' => 29.00, 'longitude' => 83.85],
            ['province_id' => 4, 'title' => 'Myagdi', 'titleNP' => 'म्याग्दी', 'headquarter' => 'Beni', 'latitude' => 28.61, 'longitude' => 83.51],
            ['province_id' => 4, 'title' => 'Parbat', 'titleNP' => 'पर्वत', 'headquarter' => 'Kusma', 'latitude' => 28.18, 'longitude' => 83.70],
            ['province_id' => 5, 'title' => 'Parasi District', 'titleNP' => 'परासी जिल्ला', 'headquarter' => 'Ramgram', 'latitude' => "null", 'longitude' => "null"],
            ['province_id' => 6, 'title' => 'Western Rukum District', 'titleNP' => 'पश्चिमी रुकुम जिल्ला', 'headquarter' => 'Musikot', 'latitude' => "null", 'longitude' => "null"]
        ];

        $dbDistricts = District::pluck('title')->toArray();
        foreach ($districts as $district) {
            if (!in_array($district['title'], $dbDistricts)) {
                unset($district['titleNP']);
                DB::table('districts')->insert($district);
            }
        }
        foreach ($districts as $district) {
            if ($dbDistrict = District::where('title', $district['title'])->first()) {
                if (!District::where('title', $district['titleNP'])->first()) {
                    if ($province = Province::where('existing_record_id', $district['province_id'])->first()) {
                        $district['title'] = $district['titleNP'];
                        unset($district['titleNP']);
                        $district['province_id'] = $province->id;
                        $district['existing_record_id'] = $dbDistrict->id;
                        $district['language_id'] = 2;
                        DB::table('districts')->insert($district);
                    }
                }
            }
        }
    }
}
