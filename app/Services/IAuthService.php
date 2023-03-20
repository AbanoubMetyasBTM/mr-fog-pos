<?php

namespace App\Services;

use Illuminate\Http\Request;

interface IAuthService {

    function loginValidation(Request $request);

    function login(Request $request) :object ;


    function forgetPasswordRequestValidation(Request $request) :object ;

    function forgetPasswordRequest(Request $request) :object ;

    function resetPasswordValidation(Request $request) :object ;

    function resetPassword(Request $request) :object ;

    function logout(Request $request) :object ;

}
