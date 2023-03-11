<?php

namespace Database\Seeders;

use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            ['name' => 'Admin Type', 'alias' => 'admin-type',],
            ['name' => 'Admin', 'alias' => 'admin'],
            ['name' => 'User',  'alias' => 'user'],
            ['name' => 'Activity', 'alias' => 'activity'],
            ['name' => 'Article', 'alias' => 'article'],
            ['name' => 'Banner',  'alias' => 'banner'],
            ['name' => 'Blog', 'alias' => 'blog'],
            ['name' => 'Blog Category', 'alias' => 'blog-category'],
            ['name' => 'Content', 'alias' => 'content'],
            ['name' => 'Download Category', 'alias' => 'download-category'],
            ['name' => 'Download', 'alias' => 'download'],
            ['name' => 'FAQ', 'alias' => 'faq'],
            ['name' => 'FAQ Category', 'alias' => 'faq-category'],
            ['name' => 'Gallery', 'alias' => 'gallery'],
            ['name' => 'Video Links', 'alias' => 'video-links'],
            ['name' => 'News Category', 'alias' => 'news-category'],
            ['name' => 'News', 'alias' => 'news'],
            ['name' => 'Notice', 'alias' => 'notice'],
            ['name' => 'PopUp',  'alias' => 'popup'],
            ['name' => 'Room List', 'alias' => 'room-list'],
            ['name' => 'Site Setting',  'alias' => 'site-setting'],
            ['name' => 'Testimonial', 'alias' => 'testimonial'],
            ['name' => 'Destination',  'alias' => 'destination'],
            ['name' => 'Itinerary',  'alias' => 'itinerary'],
            ['name' => 'Packagebanner',  'alias' => 'package_banner'],
            ['name' => 'Packages',  'alias' => 'packages'],
            ['name' => 'Imageresize',  'alias' => 'imageresize'],
            ['name' => 'Booking',  'alias' => 'booking'],
            ['name' => 'Departure',  'alias' => 'departure'],
            ['name' => 'Email Subscribe',  'alias' => 'email-subscribe'],
            ['name' => 'Seos',  'alias' => 'seos'],
            ['name' => 'Article Comment',  'alias' => 'article_comment'],
            ['name' => 'Package Route',  'alias' => 'package_route'],
            ['name' => 'Package Price Range',  'alias' => 'package_pricerange'],
            ['name' => 'Menu',  'alias' => 'menu'],
            ['name' => 'Menu Item',  'alias' => 'menu-item'],
            ['name' => 'Post', 'alias' => 'post'],
            ['name' => 'Post Category', 'alias' => 'post-category'],
            ['name' => 'Press Release', 'alias' => 'press-release'],
            ['name' => 'Tender Notice', 'alias' => 'tender-notice'],
            ['name' => 'Offer', 'alias' => 'offer'],
            ['name' => 'Service', 'alias' => 'service'],
            ['name' => 'Account Type', 'alias' => 'account-type'],
            ['name' => 'Account Type Category', 'alias' => 'account-type-category'],
            ['name' => 'ATM Location', 'alias' => 'atm-location'],
            ['name' => 'Branch', 'alias' => 'branch-directory'],
            ['name' => 'Partner', 'alias' => 'partner'],
            ['name' => 'Layout', 'alias' => 'layout'],
            ['name' => 'Remittance', 'alias' => 'remittance'],
            ['name' => 'Remittance Alliances', 'alias' => 'remittance-alliances'],
            ['name' => 'Remittance Info', 'alias' => 'remittance-info'],
            ['name' => 'Department', 'alias' => 'department'],
            ['name' => 'Grievance', 'alias' => 'grievance'],
            ['name' => 'Career', 'alias' => 'career'],
            ['name' => 'Applicant', 'alias' => 'applicant'],
            ['name' => 'Placement', 'alias' => 'placement'],
            ['name' => 'Advertisement', 'alias' => 'advertisement'],
            ['name' => 'Financial Report Category', 'alias' => 'financial-report-category'],
            ['name' => 'Financial Report', 'alias' => 'financial-report'],
            ['name' => 'Remittance Alliance Request', 'alias' => 'remittance-alliance-request'],
            ['name' => 'Remittance Alliance Contact', 'alias' => 'remittance-alliance-contact'],
            ['name' => 'Contact', 'alias' => 'contact'],
            ['name' => 'Team Category', 'alias' => 'team-category'],
            ['name' => 'Team', 'alias' => 'team'],
            ['name' => 'AGM Report Category', 'alias' => 'agm-report-category'],
            ['name' => 'AGM Report', 'alias' => 'agm-report'],
            ['name' => 'Bonus Category', 'alias' => 'bonus-category'],
            ['name' => 'Bonus', 'alias' => 'bonus'],
            ['name' => 'NAV Category', 'alias' => 'nav-category'],
            ['name' => 'NAV', 'alias' => 'nav'],
            ['name' => 'Forex', 'alias' => 'forex'],
            ['name' => 'Import', 'alias' => 'import'],
            ['name' => 'Stock Info', 'alias' => 'stock-info'],
            ['name' => 'Statistics', 'alias' => 'statistics'],
            ['name' => 'Body Menu', 'alias' => 'body-menu'],
        ];

        $dbModules = Module::pluck('alias')->toArray();
        foreach ($modules as $key => $module) {
            if (!in_array($module['alias'], $dbModules)) {
                $module['display_order'] = $key + 1;
                $module['created_at'] = Carbon::now();
                DB::table('modules')->insert($module);
            }
        }
    }
}
