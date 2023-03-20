<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\permissions\permission_pages_m;
use App\models\permissions\permissions_m;
use App\User;
use Illuminate\Http\Request;

class PermissionsController extends AdminBaseController
{


    public function __construct()
    {

        parent::__construct();


        $this->setMetaTitle("Permissions");

    }


    public function permissionsMultiAccepters(Request $request)
    {

        havePermissionOrRedirect("admin/permissions", "manage_permissions");

        $item_id       = $request->get("item_id");
        $permissionObj = permissions_m::findOrFail($item_id);

        $response = $this->new_accept_item($request);

        \Cache::forever('user_permissions_' . $permissionObj->user_id, permissions_m::where("user_id", $permissionObj->user_id)->get()->groupBy("page_name"));

        echo $response;

    }

    public function assign_all_permission(Request $request, $user_id)
    {

        havePermissionOrRedirect("admin/permissions", "assign_all_permission");

        $user_obj = User::where("user_id", $user_id)->get()->first();

        if (!is_object($user_obj)) {
            $this->returnMsgWithRedirection($request, 'admin/dashboard', "there is not user with this id");
        }

        $this->data["user_obj"] = $user_obj;

        //get all permission pages
        $all_permission_pages = permission_pages_m::getWhereSubSys($user_obj->user_role)->groupBy("page_name");


        //delete all old user permissions
        permissions_m::where("user_id", $user_id)->delete();

        foreach ($all_permission_pages as $page_key => $page_val) {
          permissions_m::create([
                "user_id"                => "$user_id",
                "page_name"              => "$page_key",
                'show_action'            => 1,
                'add_action'             => 1,
                'edit_action'            => 1,
                'delete_action'          => 1,
                'additional_permissions' => json_encode($page_val->first()->all_additional_permissions)
            ]);
        }

        \Cache::forever('user_permissions_' . $user_id, permissions_m::where("user_id", $user_id)->get()->groupBy("page_name"));

        return $this->returnMsgWithRedirection($request, 'admin/admins/assign_permission/' . $user_id, "reload");


    }

    public function assign_permission(Request $request, $user_id)
    {

        havePermissionOrRedirect("admin/permissions", "manage_permissions");

        $user_obj = User::where("user_id", $user_id)->get()->first();

        if ($this->branch_id != null && $user_obj->branch_id != $this->branch_id){
            abort(404);
            die();
        }

        if (!is_object($user_obj)) {
            $this->returnMsgWithRedirection($request, 'admin/dashboard', "there is not user with this id");
        }

        $this->data["user_obj"] = $user_obj;

        //get all permission pages
        $all_permission_pages = permission_pages_m::getWhereSubSys($user_obj->user_role)->all();
        $all_permission_pages = array_combine(convert_inside_obj_to_arr($all_permission_pages, "page_name"), $all_permission_pages);

        //get all user permissions
        $all_user_permissions = permissions_m::where("user_id", $user_id)->get()->all();
        $all_user_permissions = array_combine(convert_inside_obj_to_arr($all_user_permissions, "page_name"), $all_user_permissions);

        $this->data["all_permission_pages"] = $all_permission_pages;
        $this->data["all_user_permissions"] = $all_user_permissions;


        foreach ($all_user_permissions as $user_per_key => $user_per_val) {
            unset($all_permission_pages[$user_per_key]);
        }


        if (isset_and_array($all_permission_pages)) {

            foreach ($all_permission_pages as $page_key => $page_val) {
                permissions_m::create([
                    "user_id"   => "$user_id",
                    "page_name" => "$page_key"
                ]);

            }

            return $this->returnMsgWithRedirection($request, 'admin/admins/assign_permission/' . $user_id, "reload");

        }


        if ($request->method() == "POST") {

            foreach ($all_user_permissions as $user_per_key => $user_per_val) {
                $new_perms = $request->get("additional_perms_new" . $user_per_val->per_id);

                permissions_m::where("per_id", $user_per_val->per_id)->update([
                    "additional_permissions" => json_encode($new_perms)
                ]);
            }

            \Cache::forever('user_permissions_' . $user_id, permissions_m::where("user_id", $user_id)->get()->groupBy("page_name"));

            return $this->returnMsgWithRedirection($request, 'admin/admins/assign_permission/' . $user_id, "done");

        }


        return $this->returnView($request, "admin.subviews.permissions.user_permissions");
    }


}
