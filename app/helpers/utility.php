<?php

namespace App\helpers;

use App\Jobs\sendEmail;
use App\User;
use Illuminate\Support\Facades\Mail;

class utility
{

    public static function get_admins($ids_only = false)
    {
        $admins = User::whereIn("user_type", ["admin", "dev"])->get();

        if ($ids_only) {
            return $admins->pluck("user_id")->all();
        }

        return $admins;
    }

    public static function updateEnvFile(array $array)
    {

        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $envKey => $envValue) {

                $str               .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (is_bool($keyPosition) && (!$keyPosition || !$endOfLinePosition || !$oldLine)) {
                    $str .= "{$envKey}={$envValue}\n";
                }
                else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }

            }
        }

        $str = substr($str, 0, -1);
        file_put_contents($envFile, $str);

    }

    public static function listPhoneCodes()
    {

        $phone_codes  = [];
        $countryArray = list_phone_countries();

        $maping_countries = list_phone_codes();

        foreach ($countryArray as $key => $country) {

            $placeholder = "";

            $code = strtoupper($country[1]);

            if (isset($maping_countries[$code])) {

                $mobile_begin_with = $maping_countries[$code]["mobile_begin_with"];
                if (isset($mobile_begin_with[0])) {

                    $mobile_begin_with = $mobile_begin_with[0];

                    $phone_number_lengths = $maping_countries[$code]["phone_number_lengths"];
                    if (isset($phone_number_lengths[0])) {

                        $phone_number_lengths     = $phone_number_lengths[0];
                        $mobile_begin_with_length = strlen($mobile_begin_with);

                        $placeholder = "$mobile_begin_with";

                        $remain_number = ($phone_number_lengths - $mobile_begin_with_length);
                        if ($remain_number > 0) {
                            for ($ind = 0; $ind < $remain_number; $ind++) {
                                $placeholder .= "$ind";
                            }
                        }

                    }

                }

            }

            $flag_image_name = strtolower($country[1]);

            $phone_codes[] = [
                "code"        => "+" . $country[2],
                "name"        => $country[0],
                "placeholder" => $placeholder,
                "image"       => url("public/images/flags/$flag_image_name.png")
            ];


        }

        return $phone_codes;

    }

    public static function sendEmailToCustom(
        $emails = [],
        $data = "",
        $subject = "",
        $sender = "",
        $path_to_file = "",
        $name = "",
        $reply_to = "",
        $reply_to_name = ""
    )
    {

        if (is_array($emails) && count($emails) > 0) {
            $emails = array_diff($emails, [""]);


            if (is_array($data) && count($data) > 0) {
                $view = "email.advanced";
            }
            else {
                $data = ["default" => $data];
                $view = "email.default";
            }

            Mail::send($view, $data, function ($message) use (
                $emails, $subject, $path_to_file, $name, $reply_to, $reply_to_name
            ) {


                if ($reply_to != "" & $reply_to_name != "") {
                    $message->replyTo($reply_to, $reply_to_name);
                }


                if ($path_to_file != "" && is_file($path_to_file)) {
                    $message->attach($path_to_file);
                }

                if (count($emails) > 1)
                {
                    $message->bcc($emails)->subject($subject);
                }
                else{
                    $message->to($emails[0])->subject($subject);
                }

            });

        }

        return Mail:: failures();
    }

    public static function sendErrorLogEmail($subject, $body, $requestLog = [])
    {


        $user_email_body = \View::make("email.system.bug_email")->with([
            "header"  => $subject,
            "message" => $body,
            "getLog"  => $requestLog,
        ])->render();

        dispatch(
            (new sendEmail([
                "email"   => config('system_emails.developers'),
                "subject" => $subject,
                "data"    => $user_email_body
            ]))->onQueue("not_important_queue")
        );


    }

}
