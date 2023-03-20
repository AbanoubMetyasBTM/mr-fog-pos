<?php

Route::group([
    'middleware' => ['web','CheckLoggedIn']
], function () {

    Route::get('/send_emails','HomeController@send_emails');


    Route::post("/check_new_notifications","DashboardController@check_new_notifications");

    Route::post('/save_push_token','DashboardController@save_push_token');


});


