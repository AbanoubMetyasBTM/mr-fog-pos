<?php

function getSettingsValue($settingKey, $default = "", $notImportant = false)
{
    $getSettingValue = Cache::get("settings.{$settingKey}");


    if ($getSettingValue === null && empty($default) && !$notImportant) {
        \App\btm_form_helpers\SiteSettings::reCacheSettings();
    }

    return ($getSettingValue == null) ? $default : $getSettingValue;

}

function isLocalHost()
{
    return (
        in_array(get_client_ip(false), ["127.0.0.1", "::1"]) ||
        strpos(url("/"), "localhost") !== false
    );
}
