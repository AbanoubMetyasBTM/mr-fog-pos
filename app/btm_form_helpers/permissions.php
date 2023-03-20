<?php

namespace App\btm_form_helpers;


use App\models\permissions\permissions_m;

class permissions
{

    public static $user_id;

    public static function get_user_permissions()
    {

        $get_permissions = \Cache::get("user_permissions_" . self::$user_id);
        if ($get_permissions !== null) {
            return $get_permissions;
        }

        $get_permissions = permissions_m::get_permissions( " where per.user_id =  ".self::$user_id." " );
        $get_permissions = collect($get_permissions)->groupBy('page_name');
        $get_permissions = $get_permissions->all();

        return $get_permissions;
    }


}
