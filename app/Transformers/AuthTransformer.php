<?php

namespace App\Transformers;

class AuthTransformer extends Transformer
{

    public function transformSMSVerificationMessage($verificationNum){
        return "Your verification code is ".$verificationNum;
    }

    public function transformDataHasVerificationCode($data){
        if(isset($data["verification_code"]) && env("APP_ENV") == "production"){
            unset($data["verification_code"]);
        }

        return $data;
    }


}
