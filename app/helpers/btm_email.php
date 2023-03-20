<?php

use App\btm_form_helpers\ErrorHelper;
use App\btm_form_helpers\site_content;

function send_email_to_custom(
    $emails = array() ,
    $data = "" ,
    $subject = "",
    $sender = "" ,
    $path_to_file = "",
    $name="",
    $reply_to="",
    $reply_to_name=""
)
{

    if (is_array($emails) && count($emails) > 0)
    {
        $emails=array_diff($emails,[""]);

        if (is_array($data) && count($data) > 0)
        {
            $view = "email.advanced";
        }
        else{
            $data = [
                "header"=>$subject,
                "body"=>$data
            ];
            $view = "email.msg";
        }

        Mail::send($view,$data,function ($message) use (
            $emails , $subject, $path_to_file,$name,$reply_to,$reply_to_name
        ) {


            if($reply_to!=""&&$reply_to_name!=""){
                $message->replyTo($reply_to, $reply_to_name);
            }



            if ($path_to_file != "" && is_file($path_to_file))
            {
                $message->attach($path_to_file);
            }

            $message->to($emails)->subject($subject);
        });

    }

    return Mail:: failures();
}

function send_email_v2($attrs=[]){

    /*$array_demonstration=[
        "email"=>"",
        "subject"=>"",
        "header"=>"",
        "body"=>"",
        "lang_id"=>"",
    ];*/

    if(!isset($attrs["lang_title"])){
        $attrs["lang_title"]='en';
    }

    $site_content_data=site_content::general_get_content($attrs["lang_title"],[
        "general_keywords",
        "email_content",
    ]);

    $email_body=\View::make("email.msg")->with([
        "header"=>$attrs["header"],
        "body"=>$attrs["body"],
        "general_keywords"=>$site_content_data["general_keywords"]??"",
        "email_content"=>$site_content_data["email_content"]??"",
    ])->render();

    send_email_to_custom(
        $emails = is_array($attrs["email"])?$attrs["email"]:[$attrs["email"]] ,
        $data = $email_body ,
        $attrs["subject"]
    );

}

function convert_data_to_table_for_email($data_arr){
    $return_html="";

    foreach ($data_arr as $key=>$value){
        $return_html.="<b>".capitalize_string($key)." : </b> ".$value." <br>";
    }

    return $return_html;
}

function notifyDevelopersWhenException($exception)
{

    ErrorHelper::handleException($exception);

}
