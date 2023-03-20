<?php

Route::group([
    'middleware' => ['web']
], function () {

    #region Login
    Route::get('login','front\AuthController@login');
    Route::post('login','front\AuthController@login');

    Route::get('forget-password-request','front\AuthController@forgetPasswordRequest');
    Route::post('forget-password-request','front\AuthController@forgetPasswordRequest');

    Route::get('reset-password','front\AuthController@resetPassword');
    Route::post('reset-password','front\AuthController@resetPassword');


    Route::get("logout","logout@index");
    #endregion
});










