<?php

namespace App\btm_form_helpers;


use App\models\settings_m;

class SiteSettings
{

    private static function saveSettingsAtCache($key, $value)
    {

        \Cache::forever("settings.{$key}", $value);

    }


    public static function reCacheSettings()
    {


        $settings = settings_m::all();

        foreach ($settings as $setting) {

            self::saveSettingsAtCache($setting->setting_key, $setting->setting_value);

        }

    }

}
