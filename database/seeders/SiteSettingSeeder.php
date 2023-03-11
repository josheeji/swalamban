<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['title' => 'Site Title', 'key' => 'site_title', 'value' => 'Institute of Chartered Accountants of Nepal', 'key_group' => 'general'],
            ['title' => 'Tagline', 'key' => 'tagline', 'value' => 'Institute of Chartered Accountants of Nepal. established on July 6, 2001, is a public limited company registered under company act 2006', 'key_group' => 'general'],
            ['title' => 'Header Logo', 'key' => 'header_logo', 'value' => 'Header Logo', 'key_group' => 'general'],
            ['title' => 'Footer Logo', 'key' => 'footer_logo', 'value' => 'Footer Logo', 'key_group' => 'general'],
            ['title' => 'Fav Icon', 'key' => 'fav_icon', 'value' => 'Favicon', 'key_group' => 'general'],
            ['title' => 'Site URL', 'key' => 'site_url', 'value' => '', 'key_group' => 'general'],
            ['title' => 'Landing Pages', 'key' => 'landing_pages', 'value' => '', 'key_group' => 'general'],
            ['title' => 'Copyright', 'key' => 'copyright', 'value' => 'SKBBL, All Rights Reserved.', 'key_group' => 'general'],
            ['title' => 'Product Popup', 'key' => 'product_popup', 'value' => '0', 'key_group' => 'general'],
            ['title' => "Last Site Updated Date", 'key' => 'site_updated_date', 'value' => '', 'key_group' => 'general'],

            ['title' => 'Meta Keywords', 'key' => 'meta_keywords', 'value' => 'Meta Keywords', 'key_group' => 'seo'],
            ['title' => 'Meta Description', 'key' => 'meta_description', 'value' => 'Meta Description', 'key_group' => 'seo'],
            ['title' => 'Facebook', 'key' => 'facebook', 'value' => '', 'key_group' => 'social'],
            ['title' => 'Instagram', 'key' => 'instagram', 'value' => '', 'key_group' => 'social'],
            ['title' => 'Twitter', 'key' => 'twitter', 'value' => '', 'key_group' => 'social'],
            ['title' => 'YouTube', 'key' => 'youtube', 'value' => '', 'key_group' => 'social'],
            ['title' => 'LinkedIn', 'key' => 'linkedin', 'value' => '', 'key_group' => 'social'],
            ['title' => 'Pinterest', 'key' => 'pinterest', 'value' => '', 'key_group' => 'social'],
            ['title' => 'TikTok', 'key' => 'tiktok', 'value' => '', 'key_group' => 'social'],
            ['title' => 'Viber', 'key' => 'viber', 'value' => '', 'key_group' => 'social'],
            ['title' => 'Address', 'key' => 'address', 'value' => 'Babarmahal, Kathmandu, Nepal', 'key_group' => 'contact'],
            ['title' => 'Contact', 'key' => 'contact', 'value' => '01-5320913, 01-5909612', 'key_group' => 'contact'],
            ['title' => 'Tollfree No.', 'key' => 'tollfree-no', 'value' => '', 'key_group' => 'contact'],
            ['title' => 'Fax', 'key' => 'fax', 'value' => '', 'key_group' => 'contact'],
            ['title' => 'Email Address', 'key' => 'email_address', 'value' => 'info@skbbl.com.np', 'key_group' => 'contact'],
            ['title' => 'Post Code', 'key' => 'post_code', 'value' => '', 'key_group' => 'contact'],
            ['title' => 'Admin Email', 'key' => 'admin_email', 'value' => 'bikesh.shrestha@peacenepal.com', 'key_group' => 'contact'],
            ['title' => 'HR Email', 'key' => 'hr_email', 'value' => '', 'key_group' => 'contact'],
            ['title' => 'Preferred Language', 'key' => 'preferred_language', 'value' => '1', 'key_group' => 'general'],
            ['title' => 'Google Play', 'key' => 'google_play', 'value' => '', 'key_group' => 'others'],
            ['title' => 'App Store', 'key' => 'app_store', 'value' => '', 'key_group' => 'others'],
            ['title' => 'Remit Email', 'key' => 'remit_email', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Address', 'key' => 'remit_address', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Contact', 'key' => 'remit_contact', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Whatsapp/Viber', 'key' => 'remit_social', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Google Map', 'key' => 'remit_map', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Multi Language', 'key' => 'multi_language', 'value' => '0', 'key_group' => 'general'],
            ['title' => 'Multi Language Front', 'key' => 'multi_language_front', 'value' => '0', 'key_group' => 'general'],
            ['title' => 'Google Analytics', 'key' => 'google_analytics', 'value' => '', 'key_group' => 'others'],
            ['title' => 'Gievance Handling Officer', 'key' => 'grievance_handling_officer', 'value' => '', 'key_group' => 'grievance'],
            ['title' => 'Address', 'key' => 'grievance_address', 'value' => '', 'key_group' => 'grievance'],
            ['title' => 'Contact', 'key' => 'grievance_contact', 'value' => '', 'key_group' => 'grievance'],
            ['title' => 'Email', 'key' => 'grievance_email', 'value' => '', 'key_group' => 'grievance'],
            ['title' => "Image", 'key' => 'grievance_image', 'value' => '', 'key_group' => 'grievance'],

            ['title' => 'Author', 'key' => 'schema_author', 'value' => '', 'key_group' => 'schema'],
            ['title' => 'Creator', 'key' => 'schema_creator', 'value' => '', 'key_group' => 'schema'],
            ['title' => 'Editor', 'key' => 'schema_editor', 'value' => '', 'key_group' => 'schema'],
            ['title' => 'Publisher', 'key' => 'schema_publisher', 'value' => '', 'key_group' => 'schema'],

            ['title' => 'Name', 'key' => 'schema_home_name', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Legal Name', 'key' => 'schema_home_legal_name', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Brand', 'key' => 'schema_home_brand', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Logo', 'key' => 'schema_home_logo', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Image', 'key' => 'schema_home_image', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Founder', 'key' => 'schema_home_founder', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Address', 'key' => 'schema_home_address', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Email', 'key' => 'schema_home_email', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Telephone', 'key' => 'schema_home_telephone', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Fax Number', 'key' => 'schema_home_fax_number', 'value' => '', 'key_group' => 'schema_home'],
            ['title' => 'Description', 'key' => 'schema_home_description', 'value' => '', 'key_group' => 'schema_home'],

            ['title' => 'Page Banner', 'key' => 'remit_banner', 'value' => '', 'key_group' => 'remittance'],
            ['title' => 'Custom Css', 'key' => 'custom_css', 'value' => '', 'key_group' => 'others'],

            ['title' => 'Management Team', 'key' => 'management_team_banner', 'value' => '', 'key_group' => 'page_banner'],
            ['title' => 'Enable Banner Image', 'key' => 'banner_image', 'value' => 1, 'key_group' => 'cms'],
            ['title' => 'Enable Visible In', 'key' => 'visible_in', 'value' => 0, 'key_group' => 'cms'],
            ['title' => 'Name', 'key' => 'information_name', 'value' => 'Sudarsan', 'key_group' => 'information_officer'],
            ['title' => 'Designation', 'key' => 'information_designation', 'value' => 'Manager', 'key_group' => 'information_officer'],
            ['title' => 'Address', 'key' => 'information_address', 'value' => 'Kathmandu', 'key_group' => 'information_officer'],
            ['title' => 'Phone', 'key' => 'information_phone', 'value' => '01-554152', 'key_group' => 'information_officer'],
            ['title' => 'Mobile', 'key' => 'information_mobile', 'value' => '9841415263', 'key_group' => 'information_officer'],
            ['title' => 'Email', 'key' => 'information_email', 'value' => 'info@suryajyoti.com.np', 'key_group' => 'information_officer'],
            ['title' => "Image", 'key' => 'information_image', 'value' => '', 'key_group' => 'information_officer'],
            ['title' => "Reinsurer Image", 'key' => 'information_reinsurer_image', 'value' => '', 'key_group' => 'information_officer'],      

        ];

        // DB::table('site_settings')->truncate();

        $dbSiteSetting = SiteSetting::pluck('key')->toArray();
        foreach ($settings as $setting) {
            if (!in_array($setting['key'], $dbSiteSetting)) {
                $setting['created_at'] = Carbon::now();
                DB::table('site_settings')->insert($setting);
            }
        }


        $multiLanguage =  [
            ['title' => 'Tagline', 'key' => 'tagline', 'value' => 'सूर्य ज्योति लाइफ इन्स्योरेन्स कम्पनी लि', 'key_group' => 'general'],
            ['title' => 'Address', 'key' => 'address', 'value' => 'सानो गौचरन, काठमाडौं', 'key_group' => 'contact'],
            ['title' => 'Contact', 'key' => 'contact', 'value' => '०१-४५२३७४३,४५४६३२१,४५४६३२२', 'key_group' => 'contact'],
            ['title' => 'Copyright', 'key' => 'copyright', 'value' => 'सूर्य ज्योति लाइफ इन्स्योरेन्स कम्पनी लि। सबै अधिकार सुरक्षित।', 'key_group' => 'general'],
            ['title' => 'Name', 'key' => 'information_name', 'value' => 'सुदर्शन', 'key_group' => 'information_officer'],
            ['title' => 'Designation', 'key' => 'information_designation', 'value' => 'सूचना प्रबन्धक', 'key_group' => 'information_officer'],
            ['title' => 'Address', 'key' => 'information_address', 'value' => 'सानो गौचरन, काठमाडौं', 'key_group' => 'information_officer'],
            ['title' => 'Phone', 'key' => 'information_phone', 'value' => '०१-४५४६३२२', 'key_group' => 'information_officer'],
            ['title' => 'Mobile', 'key' => 'information_mobile', 'value' => '९८४१४१५२६३', 'key_group' => 'information_officer'],
        ];


        $dbSiteSetting = SiteSetting::where('language_id', 2)->pluck('key')->toArray();
        foreach ($multiLanguage as $setting) {
            if (!in_array($setting['key'], $dbSiteSetting)) {
                $setting['language_id'] = 2;
                $setting['created_at'] = Carbon::now();
                DB::table('site_settings')->insert($setting);
            }
        }
    }
}