<?php

Route::get('clear_cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
});

// Web Routing

Route::group([
    'middleware' => ['web']
], function () {


    Route::get('/','front\AuthController@login');
    Route::get('/not_found_page','front\PagesController@showNotFoundPage')->name('not_found_page');

});










