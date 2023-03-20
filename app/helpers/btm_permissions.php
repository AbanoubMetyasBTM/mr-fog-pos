<?php

function check_permission($all_user_permissions,$index,$action){

    if(isset($all_user_permissions[$index])){
        $get_permission=$all_user_permissions[$index]->all();
    }


    if (isset($get_permission)&&is_array($get_permission) && count($get_permission))
    {
        $get_permission = $get_permission[0];
        if (isset($get_permission->$action) && $get_permission->$action)
        {
            return true;
        }

        $additional_permissions=json_decode($get_permission->additional_permissions);
        if (is_array($additional_permissions)&&in_array($action,$additional_permissions))
        {
            return true;
        }

    }

    return false;
}

function havePermission($permission_page,$action)
{

    $user_id          = Session::get('this_user_id');
    $user_permissions = Cache::get('user_permissions_' . $user_id);

    if (!check_permission($user_permissions,$permission_page,$action))
    {
        return false;
    }

    return true;
}

function havePermissionOrRedirect($permission_page,$action,$url_redirect_to="admin/dashboard")
{

    $user_id          = Session::get('this_user_id');
    $user_permissions = Cache::get('user_permissions_' . $user_id);

    if (!check_permission($user_permissions,$permission_page,$action))
    {

        return \Redirect::
        to($url_redirect_to."?show_flash_msg=You do not have the permission to access this page&show_flash_type=error")->
        send();

        die();
    }
}
