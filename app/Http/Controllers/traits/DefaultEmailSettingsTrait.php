<?php

namespace App\Http\Controllers\traits;


use App\Jobs\sendEmail;

trait DefaultEmailSettingsTrait
{

    private function setEmailLanguage($user)
    {

        if ($user->registered_language == config("default_language.main_lang_title")) {
            return;
        }

        $langId = \Cache::get("get_lang_id_" . $user->registered_language);

        if ($langId == null) {
            return;
        }

        \Config::set("default_language.primary_lang_id", $langId);
        \Config::set("default_language.primary_lang_title", $user->registered_language);

    }

    public function setEmailSettings($user, array $customEmailSettings = []): array
    {

        #region agent email settings

        $customLogo = "";
        $siteName   = "";
        if (isset($user->user_type) && $user->user_type == "agent") {
            $customLogo = $user->agency_img_obj;
            $siteName   = $user->agency_name;
        }

        $siteLogo = get_image_from_json_obj($customLogo, showContent("logo_and_icon.logo", true), true);

        #endregion

        $this->setEmailLanguage($user);

        $emailDefaultSettings = [
            "siteLogo" => $siteLogo,
            "siteName" => $siteName,
        ];

        return array_merge($emailDefaultSettings, $customEmailSettings);

    }

    public function generalSendEmail($email, $subject, $body)
    {

        $user_email_body = \View::make("email.msg")->with([
            "header" => $subject,
            "body"   => $body,
        ])->render();

        dispatch(
            (new sendEmail([
                "email"   => $email,
                "subject" => $subject,
                "data"    => $user_email_body
            ]))->onQueue("not_important_queue")
        );

    }


}
