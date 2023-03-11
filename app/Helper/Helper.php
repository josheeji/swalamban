<?php

namespace App\Helper;

use App\Models\Module;
use App\Models\SiteSetting;
use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\SchemaOrg\Schema;

class Helper
{
    public static  function getVisibleIn()
    {
        return [
            ['id' => '1', 'placeholder' => 'Personal', 'div_id' => 'personal'],
            ['id' => '2', 'placeholder' => 'Business', 'div_id' => 'business'],
            ['id' => '3', 'placeholder' => 'All', 'div_id' => 'all']
        ];
    }

    public static function getLanguageFromId($lang_id)
    {
        $languages = self::getLanguages();

        foreach ($languages as $lang) {
            if ($lang['id'] == $lang_id) {
                return $lang['code'];
            }
        }
    }

    public static function getIdFromLanguage($lang_code)
    {
        $languages = self::getLanguages();

        foreach ($languages as $lang) {
            if ($lang['code'] == $lang_code) {
                return $lang['id'];
            }
        }
    }

    public static function loadPermission()
    {
        $permissions = [];
        if (!Auth::guest()) {
            if ($collection = (auth('admin')->user()->adminType->access)->groupBy('module_id')) {
                foreach ($collection as $module_id => $data) {
                    $permissions[$module_id]['view'] = $data[0]->view;
                    $permissions[$module_id]['add'] = $data[0]->add;
                    $permissions[$module_id]['edit'] = $data[0]->edit;
                    $permissions[$module_id]['delete'] = $data[0]->delete;
                    $permissions[$module_id]['changeStatus'] = $data[0]->changeStatus;
                }
            }
        }

        $modules = Module::whereIn('id', $collection->keys()->toArray())->pluck('id', 'alias')->toArray();

        session()->put('permissions', $permissions);
        session()->put('modules', $modules);
    }

    public static function isGateSurpassed($module_alias, $action)
    {
        if (!session()->has('permissions') && !session()->has('modules')) {
            Helper::loadPermission();
            dd('no session');
        }
        $permissions = session()->get('permissions');
        $modules = session()->get('modules');

        try {
            if (array_key_exists($module_alias, $modules) && $permissions[$modules[$module_alias]][$action])
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return false;
        }

        // $access = Helper::resetCollectionFirstArrayInstanceGetRole((auth('admin')->user()->adminType->access)->groupBy('module_id'));
        // $module_ids = $access->keys()->toArray();
        // $module_alias_array = Module::whereIn('id', $module_ids)->pluck('id', 'alias')->toArray();
        // try {
        //     if ($access[$module_alias_array[$module_alias]][$action])
        //         return true;
        //     else
        //         return false;
        // } catch (\Exception $e) {
        //     return false;
        // }
    }

    public static function isArrayGateSurpassed($module_alias, $action)
    {
        if (!session()->has('permissions') && !session()->has('modules')) {
            Helper::loadPermission();
            dd('no session');
        }
        $permissions = session()->get('permissions');
        $modules = session()->get('modules');
        foreach ($module_alias as $alias) {
            if (array_key_exists($alias, $modules) && $permissions[$modules[$alias]][$action]) {
                return true;
            }
        }


        // $access = Helper::resetCollectionFirstArrayInstanceGetRole((auth('admin')->user()->adminType->access)->groupBy('module_id'));
        // $module_ids = $access->keys()->toArray();
        // $module_alias_array = Module::whereIn('id', $module_ids)->pluck('id', 'alias')->toArray();
        // $response = false;
        // try {
        //     for ($i = 0; $i < count($module_alias); $i++) {
        //         if ($access[$module_alias_array[$module_alias[$i]]][$action])
        //             // return true;
        //             $response = true;
        //         // else
        //         // return false;
        //     }
        //     return $response;
        // } catch (\Exception $e) {
        //     return false;
        // }
    }

    public static function getCountries()
    {
        return $countries = [
            "AF" => 'Afghanistan',
            "AL" => 'Albania',
            "DZ" => 'Algeria',
            "AD" => 'Andorra',
            "AO" => 'Angola',
            "AI" => 'Anguilla',
            "AQ" => 'Antarctica',
            "AR" => 'Argentina',
            "AM" => 'Armenia',
            "AW" => 'Aruba',
            "AU" => 'Australia',
            "AT" => 'Austria',
            "AZ" => 'Azerbaidjan',
            "BS" => 'Bahamas',
            "BH" => 'Bahrain',
            "BD" => 'Bangladesh',
            "BB" => 'Barbados',
            "BY" => 'Belarus',
            "BE" => 'Belgium',
            "BZ" => 'Belize',
            "BJ" => 'Benin',
            "BM" => 'Bermuda',
            "BT" => 'Bhutan',
            "BO" => 'Bolivia',
            "BA" => 'Bosnia-Herzegovina',
            "BW" => 'Botswana',
            "BV" => 'Bouvet Island',
            "BR" => 'Brazil',
            "BN" => 'Brunei Darussalam',
            "BG" => 'Bulgaria',
            "BF" => 'Burkina Faso',
            "BI" => 'Burundi',
            "KH" => 'Cambodia',
            "CM" => 'Cameroon',
            "CA" => 'Canada',
            "CV" => 'Cape Verde',
            "KY" => 'Cayman Islands',
            "TD" => 'Chad',
            "CL" => 'Chile',
            "CN" => 'China',
            "CX" => 'Christmas Island',
            "CO" => 'Colombia',
            "KM" => 'Comoros',
            "CG" => 'Congo',
            "CK" => 'Cook Islands',
            "CR" => 'Costa Rica',
            "HR" => 'Croatia',
            "CU" => 'Cuba',
            "CY" => 'Cyprus',
            "CZ" => 'Czech Republic',
            "DK" => 'Denmark',
            "DJ" => 'Djibouti',
            "DM" => 'Dominica',
            "DO" => 'Dominican Republic',
            "TP" => 'East Timor',
            "EC" => 'Ecuador',
            "EG" => 'Egypt',
            "SV" => 'El Salvador',
            "GQ" => 'Equatorial Guinea',
            "ER" => 'Eritrea',
            "EE" => 'Estonia',
            "ET" => 'Ethiopia',
            "FK" => 'Falkland Islands',
            "FO" => 'Faroe Islands',
            "FJ" => 'Fiji',
            "FI" => 'Finland',
            "FR" => 'France',
            "GA" => 'Gabon',
            "GM" => 'Gambia',
            "GE" => 'Georgia',
            "DE" => 'Germany',
            "GH" => 'Ghana',
            "GI" => 'Gibraltar',
            "GB" => 'Great Britain',
            "GR" => 'Greece',
            "GL" => 'Greenland',
            "GD" => 'Grenada',
            "GU" => 'Guam (USA)',
            "GT" => 'Guatemala',
            "GN" => 'Guinea',
            "GW" => 'Guinea Bissau',
            "GY" => 'Guyana',
            "HT" => 'Haiti',
            "HN" => 'Honduras',
            "HK" => 'Hong Kong',
            "HU" => 'Hungary',
            "IS" => 'Iceland',
            "IN" => 'India',
            "ID" => 'Indonesia',
            "IR" => 'Iran',
            "IQ" => 'Iraq',
            "IE" => 'Ireland',
            "IL" => 'Israel',
            "IT" => 'Italy',
            "CI" => 'Ivory Coast',
            "JM" => 'Jamaica',
            "JP" => 'Japan',
            "JO" => 'Jordan',
            "KZ" => 'Kazakhstan',
            "KE" => 'Kenya',
            "KI" => 'Kiribati',
            "KW" => 'Kuwait',
            "KG" => 'Kyrgyzstan',
            "LA" => 'Laos',
            "LV" => 'Latvia',
            "LB" => 'Lebanon',
            "LS" => 'Lesotho',
            "LR" => 'Liberia',
            "LY" => 'Libya',
            "LI" => 'Liechtenstein',
            "LT" => 'Lithuania',
            "LU" => 'Luxembourg',
            "MO" => 'Macau',
            "MK" => 'Macedonia',
            "MG" => 'Madagascar',
            "MW" => 'Malawi',
            "MY" => 'Malaysia',
            "MV" => 'Maldives',
            "ML" => 'Mali',
            "MT" => 'Malta',
            "MR" => 'Mauritania',
            "MU" => 'Mauritius',
            "YT" => 'Mayotte',
            "MX" => 'Mexico',
            "FM" => 'Micronesia',
            "MD" => 'Moldavia',
            "MC" => 'Monaco',
            "MN" => 'Mongolia',
            "MS" => 'Montserrat',
            "MA" => 'Morocco',
            "MZ" => 'Mozambique',
            "MM" => 'Myanmar',
            "NA" => 'Namibia',
            "NR" => 'Nauru',
            "NP" => 'Nepal',
            "NL" => 'Netherlands',
            "AN" => 'Netherlands Antilles',
            "NT" => 'Neutral Zone',
            "NZ" => 'New Zealand',
            "NI" => 'Nicaragua',
            "NE" => 'Niger',
            "NG" => 'Nigeria',
            "NU" => 'Niue',
            "NF" => 'Norfolk Island',
            "KP" => 'North Korea',
            "NO" => 'Norway',
            "OM" => 'Oman',
            "PK" => 'Pakistan',
            "PW" => 'Palau',
            "PA" => 'Panama',
            "PG" => 'Papua New Guinea',
            "PY" => 'Paraguay',
            "PE" => 'Peru',
            "PH" => 'Philippines',
            "PN" => 'Pitcairn Island',
            "PL" => 'Poland',
            "PF" => 'Polynesia (French)',
            "PT" => 'Portugal',
            "PR" => 'Puerto Rico',
            "QA" => 'Qatar',
            "RE" => 'Reunion (French)',
            "RO" => 'Romania',
            "RU" => 'Russian Federation',
            "RW" => 'Rwanda',
            "SH" => 'Saint Helena',
            "LC" => 'Saint Lucia',
            "WS" => 'Samoa',
            "SM" => 'San Marino',
            "SA" => 'Saudi Arabia',
            "SN" => 'Senegal',
            "SC" => 'Seychelles',
            "SL" => 'Sierra Leone',
            "SG" => 'Singapore',
            "SK" => 'Slovak Republic',
            "SI" => 'Slovenia',
            "SB" => 'Solomon Islands',
            "SO" => 'Somalia',
            "ZA" => 'South Africa',
            "KR" => 'South Korea',
            "ES" => 'Spain',
            "LK" => 'Sri Lanka',
            "SD" => 'Sudan',
            "SR" => 'Suriname',
            "SZ" => 'Swaziland',
            "SE" => 'Sweden',
            "CH" => 'Switzerland',
            "SY" => 'Syria',
            "TJ" => 'Tadjikistan',
            "TW" => 'Taiwan',
            "TZ" => 'Tanzania',
            "TH" => 'Thailand',
            "TG" => 'Togo',
            "TK" => 'Tokelau',
            "TO" => 'Tonga',
            "TT" => 'Trinidad and Tobago',
            "TN" => 'Tunisia',
            "TR" => 'Turkey',
            "TM" => 'Turkmenistan',
            "TV" => 'Tuvalu',
            "UG" => 'Uganda',
            "UA" => 'Ukraine',
            "AE" => 'United Arab Emirates',
            "UK" => 'United Kingdom',
            "US" => 'United States',
            "UY" => 'Uruguay',
            "UZ" => 'Uzbekistan',
            "VU" => 'Vanuatu',
            "VA" => 'Vatican City State',
            "VE" => 'Venezuela',
            "VN" => 'Vietnam',
            "EH" => 'Western Sahara',
            "YE" => 'Yemen',
            "YU" => 'Yugoslavia',
            "ZR" => 'Zaire',
            "ZM" => 'Zambia',
            "ZW" => 'Zimbabwe'
        ];
    }

    public static function getCurrentDateTime()
    {
        return date("Y-m-d H:i:s");
    }

    public static function resetCollectionFirstArrayInstance($collection)
    {
        $new_collection = [];
        foreach ($collection as $module_id => $data) {
            $new_collection[$module_id] = $data[0];
        }

        return Collection::make($new_collection);
    }

    public static function resetCollectionFirstArrayInstanceGetRole($collection)
    {
        $new_collection = [];
        foreach ($collection as $module_id => $data) {
            $new_collection[$module_id]['view'] = $data[0]->view;
            $new_collection[$module_id]['add'] = $data[0]->add;
            $new_collection[$module_id]['edit'] = $data[0]->edit;
            $new_collection[$module_id]['delete'] = $data[0]->delete;
            $new_collection[$module_id]['changeStatus'] = $data[0]->changeStatus;
        }

        return Collection::make($new_collection);
    }

    public static function slug($string)
    {
        $new_string = preg_replace("/[^a-zA-Z0-9-\@\$ \s]/", "", strtolower(strip_tags($string)));
        $rep_string = str_replace(" ", "-", trim($new_string));
        $rep_string = str_replace('_', '-', $rep_string);
        $rep_string = preg_replace('/-+/', '-', $rep_string);
        $ret_string = preg_replace('/\'/', '', $rep_string);
        return $ret_string;
    }

    public static function get_site_title()
    {
        return isset(session('site_settings')['site_title']) ? session('site_settings')['site_title'] : '';
    }

    public static function get_tagline()
    {
        return isset(session('site_settings')['tagline']) ? session('site_settings')['tagline'] : '';
    }

    public static function get_header_logo()
    {
        return isset(session('site_settings')['header_logo']) ? session('site_settings')['header_logo'] : '';
    }

    public static function get_footer_logo()
    {
        return isset(session('site_settings')['footer_logo']) ? session('site_settings')['footer_logo'] : '';
    }

    public static function get_fav_icon()
    {
        return isset(session('site_settings')['fav_icon']) ? session('site_settings')['fav_icon'] : '';
    }

    public static function get_copyright()
    {
        return isset(session('site_settings')['copyright']) ? session('site_settings')['copyright'] : '';
    }

    public static function get_meta_keywords()
    {
        return isset(session('site_settings')['meta_keywords']) ? session('site_settings')['meta_keywords'] : '';
    }

    public static function get_meta_description()
    {
        return isset(session('site_settings')['meta_description']) ? session('site_settings')['meta_description'] : '';
    }

    public static function get_facebook()
    {
        return isset(session('site_settings')['facebook']) ? session('site_settings')['facebook'] : '';
    }

    public static function get_instagram()
    {
        return isset(session('site_settings')['instagram']) ? session('site_settings')['instagram'] : '';
    }

    public static function get_twitter()
    {
        return isset(session('site_settings')['twitter']) ? session('site_settings')['twitter'] : '';
    }

    public static function get_linkedin()
    {
        return isset(session('site_settings')['linkedin']) ? session('site_settings')['linkedin'] : '';
    }

    public static function get_pinterest()
    {
        return isset(session('site_settings')['pinterest']) ? session('site_settings')['pinterest'] : '';
    }

    public static function get_youtube()
    {
        return isset(session('site_settings')['youtube']) ? session('site_settings')['youtube'] : '';
    }

    public static function get_address()
    {
        return isset(session('site_settings')['address']) ? session('site_settings')['address'] : '';
    }

    public static function get_contact()
    {
        return isset(session('site_settings')['contact']) ? session('site_settings')['contact'] : '';
    }

    public static function get_fax()
    {
        return isset(session('site_settings')['fax']) ? session('site_settings')['fax'] : '';
    }

    public static function get_email_address()
    {
        return isset(session('site_settings')['email_address']) ? session('site_settings')['email_address'] : '';
    }

    public static function get_post_code()
    {
        return isset(session('site_settings')['post_code']) ? session('site_settings')['post_code'] : '';
    }

    public static function get_admin_email()
    {
        return isset(session('site_settings')['admin_email']) ? session('site_settings')['admin_email'] : '';
    }

    public static function get_hr_email()
    {
        return isset(session('site_settings')['hr_email']) ? session('site_settings')['hr_email'] : '';
    }

    public static function getPreferredLanaguage()
    {
        return SiteSetting::where('key', 'preferred_language')->where('is_active', 1)->value('value');
    }

    public static function getSiteSettings($settings)
    {
        return SiteSetting::whereIn('key', $settings)->where('is_active', 1)->pluck('value', 'key');
    }

    public static function getSiteSetingByKey($settings, $key)
    {
        return $settings[$key];
    }

    public static function getMultiLanguageSetting()
    {
        return isset(session('site_settings')['multi_language']) ? session('site_settings')['multi_language'] : false;
    }

    public static function getContent($id)
    {
        $menu =  Content::where('is_active', '1')->where('parent_id', $id)->orderBy('display_order', 'asc')->get();
        return (!$menu->isEmpty()) ? $menu : '';
    }

    public static function getDays()
    {
        return [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];
    }

    public static function getShift()
    {
        return [
            'Morning' => 'Morning',
            'Afternoon' => 'Afternoon',
            'Evening' => 'Evening',
            'Night' => 'Night'
        ];
    }

    public static function formatTime($time, $format = '')
    {
        switch ($format) {
            case 1:
                return Carbon::parse($time)->format('H:i:s');
            default:
                return Carbon::parse($time)->format('h:i A');
        }
    }

    public static function formatDate($date, $format = '')
    {
        if ($date == '0000-00-00' || $date == '0000-00-00 00:00:00'  || $date == null) {
            return 'NA';
        }
        $format = empty($format) ? ConstantHelper::DEFAULT_DATE_FORMAT : $format;
        switch ($format) {
            case "1":
                return Carbon::parse($date)->format('l, F jS, Y');
            case "2":
                return Carbon::parse($date);
            case "3":
                return Carbon::parse($date)->format('D jS M Y g:ia');
            case "4":
                return Carbon::parse($date)->format('D j M Y');
            case "5":
                return Carbon::parse($date)->format('M j, Y g:i a');
            case "6":
                return Carbon::parse($date)->format('d M, Y');
            case "7":
                return Carbon::parse($date)->format('M j, Y');
            case "8":
                return Carbon::parse($date)->format('g:i A');
            case "9":
                return Carbon::parse($date)->format('Y');
            case "10":
                return Carbon::parse($date)->format('m/d');
            case "11":
                return Carbon::parse($date)->format('Y/m/d');
            case "12":
                return Carbon::parse($date)->format('M j, Y');
            case 13:
                return Carbon::parse($date)->format('d');
            case 14:
                return Carbon::parse($date)->format('m');
            case 15:
                return Carbon::parse($date)->format('M');
            default:
                return substr($date, 0, 10);
        }
    }

    public static function gender()
    {
        return [
            0 => 'Female',
            1 => 'Male',
            2 => 'Other'
        ];
    }

    public static function patientType()
    {
        return [
            'New Patient' => 'New Patient',
            'Returning Patient' => 'Returning Patient',
            'Other' => 'Other'
        ];
    }

    public static function shortText($string, $limit = null)
    {
        $limit = empty($limit) ? 60 : $limit;
        if (strlen($string) < $limit) {
            $string = strip_tags($string);
        } else {
            $text = strip_tags($string);
            $cutText = substr($text, 0, $limit);
            $lastSpace = strrpos($cutText, " ");
            $shortText = substr($cutText, 0, $lastSpace);
            $string = $shortText . '...';
        }
        return $string = strip_tags($string);
    }

    public static function getLanguages()
    {
        $languages = [
            ['id' => 1, 'name' => 'English', 'code' => 'en'],
            ['id' => 2, 'name' => 'Nepali', 'code' => 'np']
        ];
        $preferredLanguage = 1;
        if (session()->exists('site_settings.preferred_language')) {
            $preferredLanguage = session('site_settings')['preferred_language'];
        }
        $sorted_language = [];
        $count = 1;
        foreach ($languages as $language_id => $data) {
            if ($data['id'] == $preferredLanguage) {
                $sorted_language[0] = $data;
            } else {
                $sorted_language[$count] = $data;
                $count++;
            }
        }
        ksort($sorted_language);

        return $sorted_language;
    }

    public static function getLanguage($language)
    {
        $languages = Helper::getLanguages();
        foreach ($languages as $key => $value) {
            if ($value['id'] == $language) {
                return $value['name'];
            }
        }
    }

    public static function getBannerPosition()
    {
        return [
            1 => 'Main',
            2 => 'Content',
            3 => 'Aside',
            4 => 'Footer'
        ];
    }

    public static function visibleIn($value, $data)
    {
        if (in_array($value, explode(',', $data))) {
            return true;
        }

        return false;
    }

    public static function newsType($value, $data)
    {
        if (in_array($value, explode(',', $data))) {
            return true;
        }

        return false;
    }

    public static function visibleInLabel($visibleIn)
    {
        $data = '';
        if (Helper::visibleIn(3, $visibleIn)) {
            // return $data = '<span class="label label-primary">All</span>';
        }
        if (Helper::visibleIn(1, $visibleIn)) {
            $data .= '<span class="label bg-purple">Personal</span> ';
        }
        if (Helper::visibleIn(2, $visibleIn)) {
            $data .= '<span class="label label-default">Business</span> ';
        }
        if (Helper::visibleIn(4, $visibleIn)) {
            $data .= '<span class="label label-default">Remittance</span> ';
        }
        if (Helper::visibleIn(5, $visibleIn)) {
            $data .= '<span class="label label-default">Trade</span>';
        }
        return $data;
    }

    public static function adVisibleInLabel($visibleIn)
    {
        $data = '';
        if (Helper::visibleIn(1, $visibleIn)) {
            $data .= '<span class="label bg-purple">Personal</span> ';
        }
        if (Helper::visibleIn(2, $visibleIn)) {
            $data .= '<span class="label label-default">Business</span> ';
        }
        if (Helper::visibleIn(3, $visibleIn)) {
            $data .= '<span class="label label-primary">Trade</span> ';
        }
        if (Helper::visibleIn(4, $visibleIn)) {
            $data .= '<span class="label label-default">Remittance</span> ';
        }
        if (Helper::visibleIn(8, $visibleIn)) {
            $data .= '<span class="label label-default">News</span> ';
        }
        if (Helper::visibleIn(9, $visibleIn)) {
            $data .= '<span class="label label-default">CSR</span> ';
        }
        if (Helper::visibleIn(10, $visibleIn)) {
            $data .= '<span class="label label-default">Content</span>';
        }

        return $data;
    }

    public static function visibleInLabelRemit($visibleIn)
    {
        $data = '';
        if (Helper::visibleIn(3, $visibleIn)) {
            $data .= '<span class="label bg-purple">Kumari</span> ';
        }
        if (Helper::visibleIn(2, $visibleIn)) {
            $data .= '<span class="label label-default">Overseas</span> ';
        }
        if (Helper::visibleIn(1, $visibleIn)) {
            $data .= '<span class="label label-default">Local</span> ';
        }

        return $data;
    }

    public static function newsTypeLabel($type)
    {
        $data = '';
        if (Helper::newsType(1, $type)) {
            $data .= '<span class="label bg-purple">News</span> ';
        }
        if (Helper::newsType(2, $type)) {
            $data .= '<span class="label label-default">CSR</span> ';
        }

        return $data;
    }

    public static function locale()
    {
        $firstParam = request()->segment(1);
        if ($firstParam == 'public') {
            $firstParam = Request::segment(2);

        }
        if ($firstParam == 'admin') {
            return session('site_settings')['preferred_language'];
        }

        return session()->get('locale_id');
    }

    public static function searchKeyword($keyword, $string)
    {
        $string = ($string);
        $string = strip_tags($string);
        $string = htmlentities($string);
        $keyword = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $keyword);
        // $keyword = preg_replace("/[^a-zA-Z]/", "", $keyword);

        $regex = "/[A-Z][^\\.;\\?\\!]*({$keyword})[^\\.;\\?\\!]*/";
        $string = preg_replace('/(\\d+)\\.(\\d+)/', "$1,$2", $string);
        $response = [];
        if (preg_match_all($regex, $string, $match)) {
            foreach ($match[0] as &$str)
                $str = preg_replace('/(\\d+),(\\d+)/', "$1.$2", $str);
            $response[] = $match;
        }
        $keywords = explode(' ', $keyword);
        foreach ($keywords as $keyword) {
            $regex = "/[A-Z][^\\.;\\?\\!]*({$keyword})[^\\.;\\?\\!]*/i";
            $string = preg_replace('/(\\d+)\\.(\\d+)/i', "$1,$2", $string);
            if (preg_match_all($regex, $string, $match)) {
                foreach ($match[0] as &$str)
                    $str = preg_replace('/(\\d+),(\\d+)/', "$1.$2", $str);
                $response[] = $match;
            }
        }

        return $response;
    }

    public static function remitAddress()
    {
        return isset(session('site_settings')['remit_address']) ? session('site_settings')['remit_address'] : '';
    }

    public static function remitEmail()
    {
        return isset(session('site_settings')['remit_email']) ? session('site_settings')['remit_email'] : '';
    }

    public static function remitContact()
    {
        return isset(session('site_settings')['remit_contact']) ? session('site_settings')['remit_contact'] : '';
    }

    public static function remitSocial()
    {
        return isset(session('site_settings')['remit_social']) ? session('site_settings')['remit_social'] : '';
    }

    public static function remitBanner()
    {
        return isset(session('site_settings')['remit_banner']) ? session('site_settings')['remit_banner'] : '';
    }

    public static function grievanceHandler()
    {
        return isset(session('site_settings')['grievance_handling_officer']) ? session('site_settings')['grievance_handling_officer'] : '';
    }

    public static function grievanceAddress()
    {
        return isset(session('site_settings')['grievance_address']) ? session('site_settings')['grievance_address'] : '';
    }

    public static function grievanceContact()
    {
        return isset(session('site_settings')['grievance_contact']) ? session('site_settings')['grievance_contact'] : '';
    }

    public static function grievanceEmail()
    {
        return isset(session('site_settings')['grievance_email']) ? session('site_settings')['grievance_email'] : '';
    }

    public static function grievanceImage()
    {
        return isset(session('site_settings')['grievance_image']) ? session('site_settings')['grievance_image'] : '';
    }

    public static function googleAnalytics()
    {
        return isset(session('site_settings')['google_analytics']) ? session('site_settings')['google_analytics'] : '';
    }

    public static function schemaAuthor()
    {
        return isset(session('site_settings')['schema_author']) ? session('site_settings')['schema_author'] : '';
    }

    public static function schemaCreator()
    {
        return isset(session('site_settings')['schema_creator']) ? session('site_settings')['schema_creator'] : '';
    }

    public static function schemaEditor()
    {
        return isset(session('site_settings')['schema_editor']) ? session('site_Settings')['schema_editor'] : '';
    }

    public static function schemaPublisher()
    {
        return isset(session('site_settings')['schema_publisher']) ? session('site_settings')['schema_publisher'] : '';
    }

    public static function customCss()
    {
        return isset(session('site_settings')['custom_css']) ? '<style>' . json_decode(session('site_settings')['custom_css']) . '</style>' : '';
    }

    public static function banner($page)
    {
        switch ($page) {
            case 'management_team_banner':
                return isset(session('site_settings')['management_team_banner']) ? session('site_settings')['management_team_banner'] : '';
        }
    }

    public static function schemaHome()
    {
        if ($data = SiteSetting::where('key_group', 'schema_home')->where('is_active', 1)->pluck('value', 'key')) {
            $schema = Schema::bankOrCreditUnion()
                ->name($data['schema_home_name'])
                ->legalName($data['schema_home_legal_name'])
                ->brand($data['schema_home_brand'])
                ->logo($data['schema_home_logo'])
                ->image($data['schema_home_image'])
                ->founder($data['schema_home_founder'])
                ->address($data['schema_home_address'])
                ->email($data['schema_home_email'])
                ->telephone($data['schema_home_telephone'])
                ->faxNumber($data['schema_home_fax_number'])
                ->description($data['schema_home_description'])
                ->url(url('/'));
        }

        return $schema->toScript();
    }

    public static function preferredSize($module, $type)
    {
        $size = 'N.A.';
        switch ($module) {
            case 'content':
                $size = $type == 'image' ? '800x600px' : '1300x280px';
                break;
            case 'blog':
                $size = $type == 'image' ? '600x700px' : '1300x280px';
                break;
            case 'news':
                $size = $type == 'image' ? '800x600px' : '1300x280px';
                break;
            case 'offer':
                $size = $type == 'image' ? '800x600px' : '800x600px';
                break;
            case 'account-type':
                $size = $type == 'image' ? '800x600px' : '1300x280px';
                break;
            case 'team':
                $size = $type == 'image' ? '140x160px' : '1300x280px';
                break;
        }
        return $size;
    }

    public static function getInterestTypes()
    {
        $types = [
            'current_saving' => 'Current and Saving Deposit Products',
            'fixed_deposits' => 'Fixed Deposits',
            'garima_swasthya' => 'Garima Swasthya Surakshit Nikchhep Yojana (1 Year Fixed Deposit) (Conditions are applicable.)',
            'fcy_deposit' => 'FCY Deposit',
            'loan_advances' => 'Loan And Advances'
        ];

        return $types;
    }
}
