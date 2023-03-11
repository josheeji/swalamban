<?php

namespace App\Helper;

use App\Models\SiteSetting;

class SettingHelper
{
    public static function loadOptions()
    {
        $data = [];
        $options = SiteSetting::get();
        foreach ($options as $option) {
            if ($option->language_id == 2) {
                $data[$option->key . "_np"] = $option->value;
            } else {
                $data[$option->key] = $option->value;
            }
        }
        session(['site_settings' => $data]);
    }

    public static function setting($key)
    {
        $option = [];
        $data = '';
        if (session()->exists('site_settings')) {
            $option = session()->get('site_settings');
        }
        if (array_key_exists($key, $option)) {
            $data = $option[$key];
        }
        return $data;
    }

    public static function multiLangSetting($key)
    {
        $option = [];
        $data = '';
        if (session()->exists('site_settings')) {
            $option = session()->get('site_settings');
        }
        if (array_key_exists($key . '_np', $option)) {
            $data = $option[$key];
        }
        return $data;
    }
}