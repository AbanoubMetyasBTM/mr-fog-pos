<?php

namespace App\Jobs;

use App\helpers\utility;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class sendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $subject;
    public $body;
    public $from;
    public $name;
    public $reply_to;
    public $reply_to_name;
    public $smtp_setting;

    public $langId;
    public $langTitle;

    private function getEmails(array $attrs){

        if(isset($attrs["user_ids"])){
            return User::getUsersByIds($attrs["user_ids"])->pluck("email")->all();
        }

        return $attrs["email"] ?? "";

    }

    private function getBody(array $attrs){

        if (isset($attrs["pure_body"])){
            return \View::make("email.default_with_main_layout")->with([
                "body" => $attrs["pure_body"],
            ])->render();
        }

        return $attrs["data"];

    }


    public function __construct(array $attrs = [])
    {

//        $attrs = [
//            "user_ids"      => "",
//            "email"         => "",
//            "subject"       => "",
//            "pure_body"     => "",
//            "body"          => "",
//            "from"          => "",
//            "name"          => "",
//            "reply_to"      => "",
//            "reply_to_name" => "",
//            "smtp_setting"  => "",
//        ];

        if(!isset($attrs["data"]) && isset($attrs["body"])){
            $attrs["data"] = $attrs["body"];
        }

        $this->email         = $this->getEmails($attrs);
        $this->subject       = $attrs["subject"] ?? "";
        $this->body          = $this->getBody($attrs);
        $this->from          = $attrs["from"] ?? "";
        $this->name          = $attrs["name"] ?? "";
        $this->reply_to      = $attrs["reply_to"] ?? "";
        $this->reply_to_name = $attrs["reply_to_name"] ?? "";
        $this->smtp_setting  = $attrs["smtp_setting"] ?? "";


    }


    public function handle()
    {

        if (isset_and_array($this->smtp_setting) && $this->smtp_setting["tested"]) {
            $config = [
                'driver'     => 'smtp',
                'host'       => $this->smtp_setting["host"],
                'port'       => $this->smtp_setting["port"],
                'username'   => $this->smtp_setting["username"],
                'password'   => $this->smtp_setting["password"],
                'encryption' => $this->smtp_setting["encryption"]
            ];

            if (filter_var($this->smtp_setting["username"], FILTER_VALIDATE_EMAIL)) {
                $this->from = $this->smtp_setting["username"];
            }
            else {
                $this->from = $this->smtp_setting["user_email"];
            }


            Config::set('mail', $config);

            try {
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();

                $this->send_to_email();
            }
            catch (\Swift_TransportException $exception) {
                echo "error when sending to $this->email \n";

                return;
            }

        }
        else {
            $this->send_to_email();
        }

    }

    public function send_to_email()
    {

        if(!is_array($this->email)){
            $this->email = [$this->email];
        }

        utility::sendEmailToCustom(
            $this->email,
            $this->body,
            $this->subject,
            $this->from,
            "",
            $this->name,
            $this->reply_to,
            $this->reply_to_name
        );

    }


}
