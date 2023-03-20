<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\FrontController;
use App\Services\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends FrontController
{

    public $service;

    public function __construct(
        IAuthService $service
    )
    {
        parent::__construct();

        $this->service = $service;

    }

    private function redirectBasedOnUserType(Request $request, string $userType){

        $redirectLink = "admin/dashboard";

        return redirect()->intended($redirectLink);

    }

    public function login(Request $request)
    {

        $this->data["meta_title"]    = showContent("site_meta.login_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.login_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.login_meta_keywords");

        if (is_object(Auth::user()) && Auth::user()->is_active) {
            return $this->redirectBasedOnUserType($request, Auth::user()->user_type);
        }

        if($request->method()=="POST"){

            $request["country_code"] = getIpCountryCode();

            $validator = $this->service->loginValidation($request);
            $validator = $this->returnValidatorMsgs($validator);
            if ($validator !== true){
                return $validator;
            }

            $loginData = $this->service->login($request)->content();

            $handleApi = $this->handleApiResponse($request, $loginData);
            if($handleApi !== true){
                return $handleApi;
            }

            $loginData = $this->getApiResponseData($loginData);

            return $this->redirectBasedOnUserType($request,$loginData->type);

        }

        return $this->returnView($request,"front.subviews.authentication.login");

    }


    public function forgetPasswordRequest(Request $request)
    {

        $this->data["meta_title"]    = showContent("site_meta.forget_password_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.forget_password_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.forget_password_meta_keywords");

        if($request->method()=="POST"){

            if (checkRecaptcha($request) !== true) {
                return [
                    "error"          => showContent("support.you_must_verify_that_you_are_not_robot"),
                    "reload_captcha" => true,
                ];
            }

            $validator = $this->service->forgetPasswordRequestValidation($request);
            $validator = $this->returnValidatorMsgs($validator);
            if ($validator !== true){
                return $validator;
            }

            $apiData = $this->service->forgetPasswordRequest($request)->content();


            $handleApi = $this->handleApiResponse($request, $apiData);
            if($handleApi !== true){
                return $handleApi;
            }


            return $this->returnMsgWithRedirection(
                $request,
                "/reset-password?email={$this->getApiResponseData($apiData)->email}",
                $this->getApiResponseMessage($apiData)
            );


        }

        return $this->returnView($request,"front.subviews.authentication.forget_password_request");

    }

    public function resetPassword(Request $request)
    {

        $this->data["email_value"] = $request->get("email");

        if($request->method()=="POST"){

            $validator = $this->service->resetPasswordValidation($request);
            $validator = $this->returnValidatorMsgs($validator);
            if ($validator !== true){
                return $validator;
            }

            $apiData = $this->service->resetPassword($request)->content();

            $handleApi = $this->handleApiResponse($request, $apiData);
            if($handleApi !== true){
                return $handleApi;
            }

            return $this->returnMsgWithRedirection(
                $request,
                "/login",
                $this->getApiResponseMessage($apiData)
            );


        }

        return $this->returnView($request,"front.subviews.authentication.reset_password");

    }


}
