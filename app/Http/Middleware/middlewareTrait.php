<?php

namespace App\Http\Middleware;

use App\models\attachments_m;
use App\models\langs_m;
use App\models\settings_m;

trait middlewareTrait
{

    public function setupConfig()
    {

        #region set timezone config

//            $getDefaultTimezone = getSettingsValue('timezone','Asia/Riyadh');
//            date_default_timezone_set($getDefaultTimezone);

        #endregion

        #region set mail config

//        config([
//            "mail.driver"           => getSettingsValue('mail_type'),
//            "mail.host"             => getSettingsValue('smtp_host'),
//            "mail.port"             => getSettingsValue('smtp_port'),
//            "mail.from.address"     => getSettingsValue('email'),
//            "mail.from.name"        => getSettingsValue('name'),
//            "mail.encryption"       => getSettingsValue('smtp_ssl'),
//            "mail.username"         => getSettingsValue('smtp_user'),
//            "mail.password"         => getSettingsValue('smtp_pass'),
//        ]);

        #endregion

    }

    public function getLangId(string $langTitle)
    {

        $langId = \Cache::get("get_lang_id_".$langTitle);

        if($langId !== null){
            return $langId ;
        }

        $this->loadLangTitlesAtCache();

        $langId = \Cache::get("get_lang_id_".$langTitle);

        if($langId === null){
            return 0;
        }

        return $langId ;
    }

    public function loadLangTitlesAtCache(){

        $allLangs = langs_m::all();
        foreach ($allLangs  as $lang){
            \Cache::put("get_lang_id_".$lang->lang_title,$lang->lang_id);
        }

    }


}
