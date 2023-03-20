<?php

namespace App\Http\Controllers\dev;

use App\Http\Controllers\DevController;
use App\models\permissions\permission_pages_m;
use App\models\permissions\permissions_m;
use App\User;
use Illuminate\Http\Request;

class Permissions extends DevController
{

    public function show_all_permissions_pages(Request $request)
    {

        $this->data["all_permissions_pages"] = permission_pages_m::all();

        return $this->returnView($request, "dev.subviews.permissions_pages.show");

    }

    public function save_permission_page(Request $request, $permission_page_name = null)
    {

        $permission_page_data = "";

        if ($permission_page_name != null) {
            $permission_page_data = permission_pages_m::findOrFail($permission_page_name);
        }

        $this->data["permission_page_data"] = $permission_page_data;

        if ($request->method() == "POST") {

            $this->validate($request, [
                "page_name" => "required",
                "sub_sys"   => "required"
            ]);


            $request["all_additional_permissions"] = array_diff($request["all_additional_permissions"], [""]);

            // update
            if ($permission_page_name != null) {
                permission_pages_m::update($request->all());
            }
            else {
                $check                = permission_pages_m::create($request->all());
                $permission_page_name = $check->page_name;
            }

            return $this->returnMsgWithRedirection(
                $request,
                'dev/permissions/permissions_pages/save/' . string_safe($permission_page_name),
                "Done"
            );
        }

        return $this->returnView($request, "dev.subviews.permissions_pages.save");

    }

    public function delete_permission_page(Request $request)
    {
        $this->general_remove_item($request);
    }

    public function convert_db_to_files(Request $request)
    {

        if (!\Schema::hasTable("permission_pages")) {

            return $this->returnMsgWithRedirection(
                $request,
                "dev/dashboard",
                "DB has no permission_pages table"
            );

        }

        $rows = \DB::table("permission_pages")->get();

        foreach ($rows as $row) {
            permission_pages_m::create((array)$row);

            \DB::table("permission_pages")->where("per_page_id", $row->per_page_id)->delete();
        }


        return $this->returnMsgWithRedirection(
            $request,
            "dev/dashboard",
            "Done"
        );

    }

    public function assign_permission_for_this_user(Request $request)
    {

        $user_id = $this->user_id;

        $user_obj = User::where("user_id", $user_id)->get()->first();

        if (!is_object($user_obj)) {
            $this->returnMsgWithRedirection($request, 'admin/dashboard', "there is not user with this id");
        }

        $this->data["user_obj"] = $user_obj;

        //get all permission pages
        $all_permission_pages = permission_pages_m::all()->groupBy("page_name");


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


}
