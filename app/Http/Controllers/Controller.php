<?php

namespace App\Http\Controllers;

use App\models\langs_m;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $data              = [];
    public $current_user_data = "";
    public $user_id           = 1;
    public $primary_lang_id;
    public $primary_lang_title;

    public function __construct()
    {


        $this->primary_lang_title = config("default_language.primary_lang_title");

        $this->checkLoggedUser();
        $this->generalSettings();

    }

    public function getAllLangsForFront()
    {

        $all_langs = \Cache::get('all_langs');

        if ($all_langs == null || $all_langs->count()==0) {
            $all_langs = langs_m::where("lang_is_active",1)->get();
            \Cache::set('all_langs', $all_langs);
        }

        return $all_langs;
    }

    public function checkLoggedUser()
    {
        $current_user               = Auth::user();
        $this->data["current_user"] = null;

        if (is_object($current_user)) {
            $this->data["current_user"] = $current_user;
            $this->current_user_data    = $current_user;
            $this->user_id              = $current_user->user_id;
        }
    }

    public function generalSettings()
    {

        $app_name = getSettingsValue('site_name',env('APP_NAME'));

        $this->data["meta_title"]    = $app_name;
        $this->data["meta_desc"]     = $app_name;
        $this->data["meta_keywords"] = $app_name;

    }


    public function returnView(Request $request, $viewPath, $main_dir = "")
    {

        if($main_dir == ""){
            $main_dir = "front";
        }

        if ($request->ajax() || $request->get("load_inner")) {
            if ($request->get("do_not_ajax") != "") {
                echo "<input type='hidden' class='do_not_ajax_class'>";
            }

            echo "<input type='hidden' class='get_ajax_title' value='" . $this->data["meta_title"] . "'>";
            return view($viewPath, $this->data);
        }
        else {
            $this->data["viewPath"] = $viewPath;

            return view("$main_dir.subviews.include_inner_view", $this->data);
        }
    }

    public function returnMsgWithRedirection(Request $request, $redirectLink, $msg, $refresh = false)
    {

        if ($request->ajax() || $request->get("load_inner")) {
            return [
                "msg"      => $msg,
                "redirect" => (strpos($redirectLink, "http") !== false) ? $redirectLink : langUrl($redirectLink),
                "refresh"  => $refresh
            ];
        }

        $redirectLink = $redirectLink."?show_flash_msg=".$msg;

        \redirect($redirectLink)->send();
        die();

    }

    public function returnValidatorMsgs($validator)
    {

        if (count($validator->messages()) > 0) {
            return [
                "error" => implode("<br>", $validator->messages()->all())
            ];
        }

        return true;

    }

    public function returnErrorMessages(Request $request, string $msg)
    {

        if ($request->ajax() || $request->get("load_inner")) {
            return [
                "error" => $msg,
            ];
        }

        $msg          = "<div class='alert alert-error'>$msg</div>";
        $redirectLink = pureLangUrl("/") . "?show_flash_msg=" . $msg;

        \redirect($redirectLink)->with("msg", $msg)->send();
        die();

    }

    public function handleApiResponse(Request $request, $response)
    {

        if (is_array($response)) {
            $response = (object)$response;
        }
        else if(is_object($response)){
            $response = json_decode($response->content());
        }
        else{
            $response = json_decode($response);
        }

        if (!is_object($response)) {
            return $this->returnErrorMessages($request, "Invalid Response");
        }

        //406 represent not verified user
        if ($response->Code == 406) {
            Auth::logout();

            return $this->returnMsgWithRedirection(
                $request,
                "/account-verification?email={$request->get("email")}",
                $response->Message
            );
        }

        // in case of flight booking prices is changed
        if ($response->Code == 409) {
            return $this->returnMsgWithRedirection(
                $request,
                url()->current(),
                $response->Message,
                true
            );
        }

        if (
        !(
            $response->Code == Response::HTTP_OK ||
            $response->Code == Response::HTTP_CREATED
        )
        ) {

            //HTTP_PAYMENT_REQUIRED represents at our system that is should redirect to homepage
            //and make search again
            if ($response->Code == Response::HTTP_PAYMENT_REQUIRED) {
                return $this->returnMsgWithRedirection($request, "/", $response->Message);
            }

            //this HTTP_METHOD_NOT_ALLOWED represents you do not have enough balance to complete your booking
            if ($response->Code == Response::HTTP_METHOD_NOT_ALLOWED) {
                return $this->returnErrorMessages($request, showContent("general_keywords.service_is_not_available"));
            }

            if ($response->Code == Response::HTTP_INTERNAL_SERVER_ERROR) {

                if (env('APP_DEBUG') == true){
                    return $this->returnErrorMessages($request, $response->Message);
                }

                return $this->returnErrorMessages($request, showContent("general_keywords.please_try_again_later"));
            }

            return $this->returnErrorMessages($request, $response->Message);

        }

        return true;
    }

    public function getApiResponseData($response)
    {

        if (is_array($response)) {
            $response = (object)$response;
        }
        else if(is_object($response)){
            $response = json_decode($response->content());
        }
        else{
            $response = json_decode($response);
        }

        return $response->Data;

    }

    public function getApiResponseMessage($response)
    {

        if (is_array($response)) {
            $response = (object)$response;
        }
        else if(is_object($response)){
            $response = json_decode($response->content());
        }
        else{
            $response = json_decode($response);
        }

        return $response->Message;

    }


}
