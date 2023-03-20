<?php

namespace App\Services\Implementation;

use App\Jobs\sendEmail;
use App\Jobs\sendSMS;
use App\Services\IAuthService;
use App\Transformers\AuthTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthService extends CommonUserAndAuth implements IAuthService
{


    protected $userTransformer;
    protected $authTransformer;

    public function __construct(
        UserTransformer $userTransformer,
        AuthTransformer $authTransformer
    )
    {

        parent::__construct($userTransformer);

        $this->userTransformer = $userTransformer;
        $this->authTransformer = $authTransformer;

    }


    //done
    public function loginValidation(Request $request)
    {

        $messages = [
            'field.required'    => showContent("general_keywords.login_field_is_required"),
            'password.required' => showContent("general_keywords.password_is_required"),
        ];

        $rules = [
            'field'    => 'required',
            'password' => 'required',
        ];

        return Validator::make(clean($request->all()), $rules, $messages);

    }


    //done
    public function login(Request $request): object
    {

        $data       = clean($request->all());
        $loginField = $data["field"];
        $password   = $data['password'];

        $loginFieldType = "username";
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $loginFieldType = "email";
        }
        elseif (preg_match("/^[0-9]{9,10}$/", $loginField)) {
            $loginField     = ltrim($loginField, '0');
            $loginFieldType = "phone";
        }

        $check = Auth::attempt([
            "{$loginFieldType}" => $loginField,
            'password'          => $password,
        ]);

        if (!$check) {
            return $this->messageHandler->getJsonBadRequestErrorResponse(showContent("authentication.invalid_email_or_password"));
        }

        return $this->afterLogin($request, Auth::user());

    }

    //done
    private function afterLogin(Request $request, object $user)
    {

        $allHeaders               = clean($request->headers->all());
        $allHeaders["ip_address"] = clean($request->ip());

        $data['user_id'] = $user->user_id;

        $get_user = User::getUserProfile($user->user_id);

        if ($get_user->user_is_blocked || $get_user->is_active == 0) {
            Auth::logout();
            return $this->messageHandler->getJsonBadRequestErrorResponse(showContent("authentication.admin_blocked_you_from_login"));
        }


        $request->session()->put('password_changed_at', $user->password_changed_at);


        if (count($allHeaders) > 0) {
            $allHeaders["user_id"]         = $user->user_id;
            $allHeaders["last_login_date"] = Carbon::now()->toDateTimeString();
        }

        $request->session()->put('this_user_id', $user->user_id);
        $request->session()->put('this_user_type', $user->user_type);
        $request->session()->save();

        $get_user        = $this->userTransformer->transformLoginUser($get_user->toArray());

        return $this->messageHandler->postJsonSuccessResponse("", $get_user);

    }


    public function logout(Request $request): object
    {

        $getUser = Auth::user();

        if (!is_object($getUser)) {
            return $this->messageHandler->getJsonSuccessResponse("", []);
        }

        $userTokens = $getUser->tokens ?? [];

        foreach ($userTokens as $token) {
            $token->revoke();
        }

        $headers    = $request->headers->all();
        $push_token = $headers["device-push-token"][0];

        return $this->messageHandler->getJsonSuccessResponse("", []);

    }


    public function forgetPasswordRequestValidation(Request $request): object
    {

        $messages = [
            'field.required' => showContent("authentication.field_is_required"),
        ];

        $rules = [
            'field' => 'required',
        ];

        return Validator::make(clean($request->all()), $rules, $messages);

    }

    public function forgetPasswordRequest(Request $request): object
    {

        $data = clean($request->all());


        $loginField     = $data["field"];
        $loginFieldType = "username";
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $loginFieldType = "email";
        }
        elseif (preg_match("/^[0-9]{9,10}$/", $loginField)) {
            $loginFieldType = "phone";
        }
        $loginField     = ltrim($loginField, '0');

        $user = User::getUser([
            "{$loginFieldType}" => $loginField,
        ]);

        if (!is_object($user)) {
            return $this->messageHandler->getJsonBadRequestErrorResponse(showContent("authentication.user_not_exist"));
        }

        $passwordResetCode     = $this->generateVerificationCode();
        $passwordResetExpireAt = Carbon::now()->addHour(3);

        User::updateUser([
            'password_reset_code'      => $passwordResetCode,
            'password_reset_expire_at' => $passwordResetExpireAt,
        ], $user->user_id);

        #region send email

        $user_email_body = \View::make("email.registration.reset_password")->with([
            "user_obj"            => $user,
            "password_reset_code" => $passwordResetCode,
        ])->render();

        dispatch(
            (new sendSMS(
                $user->phone_code,
                $user->phone,
                "Password reset code is {$passwordResetCode}"
            ))
        );


        if (!empty($user->email)) {
            dispatch(
                (new sendEmail([
                    "email"   => $user->email,
                    "subject" => "Reset Password",
                    "data"    => $user_email_body
                ]))->onQueue("not_important_queue")
            );
        }

        #endregion

        $msg = showContent("authentication.reset_password_code_is_sent_to_your_email_and_sent_as_sms");

        $return_data = [
            'user_enc_id'         => $user->user_enc_id,
            "password_reset_code" => null,
        ];

        return $this->messageHandler->postJsonSuccessResponse($msg, $return_data);

    }

    public function resetPasswordValidation(Request $request): object
    {

        $messages = [
            'user_enc_id.required' => showContent("authentication.user_enc_id_is_required"),
            'reset_code.required'  => showContent("authentication.password_reset_code_is_required"),
            'password.required'    => showContent("authentication.password_is_required"),
            'password.min'         => ShowContent("authentication.password_should_be_more_than_6_characters"),
            'password.confirmed'   => ShowContent("authentication.your_password_is_mismatched"),
        ];

        $rules = [
            'user_enc_id' => 'required',
            'reset_code'  => 'required',
            'password'    => 'required|min:6|confirmed',
        ];

        return Validator::make(clean($request->all()), $rules, $messages);

    }

    public function resetPassword(Request $request): object
    {

        $data = clean($request->all());
        $user = User::getUser([
            "user_enc_id" => $data["user_enc_id"],
        ]);

        if (!is_object($user)) {
            return $this->messageHandler->getJsonBadRequestErrorResponse(showContent("authentication.user_not_exist"));
        }

        if (!($user->password_reset_code == $data['reset_code'] && $user->password_reset_expire_at >= Carbon::now())) {
            return $this->messageHandler->getJsonBadRequestErrorResponse(showContent("authentication.invalid_reset_code"));
        }


        User::updateUser([
            'password_reset_code'      => '',
            'password_reset_expire_at' => null,
            'password'                 => bcrypt($data['password'])
        ], $user->user_id);


        $msg = showContent("authentication.you_did_reset_password_successfully") . " " . showContent("authentication.please_login");

        return $this->messageHandler->postJsonSuccessResponse($msg, []);


    }


}
