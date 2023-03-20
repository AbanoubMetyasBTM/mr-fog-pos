<?php

Route::group([
    'middleware' => 'check_dev',
    'prefix'     => 'dev',
    'namespace'  => 'dev'
], function () {

//generate_edit_content
    Route::get("dashboard", 'GenerateSiteContent@show_all_methods');

    Route::get("generate_edit_content/show_all", 'GenerateSiteContent@show_all_methods');
    Route::get("generate_edit_content/save/{method_name?}", 'GenerateSiteContent@save_method');
    Route::post("generate_edit_content/save/{method_name?}", 'GenerateSiteContent@save_method');

    Route::get("generate_edit_content/convert_db_to_files", 'GenerateSiteContent@convert_db_to_files');
//END generate_edit_content


    //permissions
    Route::get("permissions/permissions_pages/show_all_permissions_pages", 'Permissions@show_all_permissions_pages');

    Route::get("permissions/permissions_pages/save/{permission_page_name?}", 'Permissions@save_permission_page');
    Route::post("permissions/permissions_pages/save/{permission_page_name?}", 'Permissions@save_permission_page');

    Route::get("permissions/assign_permission_for_this_user", 'Permissions@assign_permission_for_this_user');

    Route::get("permissions/permissions_pages/convert_db_to_files", 'Permissions@convert_db_to_files');

    Route::post("permissions/permissions_pages/delete", 'Permissions@delete_permission_page');

    //END permissions


});

