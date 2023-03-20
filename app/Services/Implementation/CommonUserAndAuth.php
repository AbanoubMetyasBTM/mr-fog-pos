<?php

namespace App\Services\Implementation;

use App\Services\ICommonUserAndAuth;
use App\Services\MainService;
use App\Transformers\UserTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;


class CommonUserAndAuth extends MainService implements ICommonUserAndAuth {

    protected $userAdapter;
    protected $phoneValidationAdapter;
    protected $transform;

    public function __construct(
        UserTransformer $transform
    ) {

        parent::__construct();

        $this->transform                = $transform;

    }

    public function checkEmailIsUnique(string $email, string $userType, ?int $user_id = null){

        $check_user  = User::getUserByEmailAndType($email, $userType);

        //to check if there's a user with the same phone or not
        if(is_object($check_user))
        {
            if($user_id != null && $check_user->user_id == $user_id){
                return $this->messageHandler->getJsonBadRequestErrorResponse(Lang::get("user.same_user_email"));
            }

            return $this->messageHandler->getJsonValidationErrorResponse(Lang::get("user.unique_email"));
        }

        return true;
    }

    public function reGenerateUserVerificationCode(object $user):string
    {
        $verification_code             = $this->generateVerificationCode();
        $verification_code_expiration  = Carbon::now()->addHour(3);

        User::updateUser([
            "verification_code"            => $verification_code,
            "verification_code_expiration" => $verification_code_expiration,
        ],$user->user_id);

        return $verification_code;
    }


    public function generateVerificationCode():int
    {
        return rand(1000,9999);
    }


}
