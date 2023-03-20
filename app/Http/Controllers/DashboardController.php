<?php

namespace App\Http\Controllers;

use App\btm_form_helpers\permissions;
use App\models\branch\branches_m;
use App\models\langs_m;
use App\models\notification_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public $current_user_data;
    public $user_permissions;
    public $branch_id  = null;
    public $branchData = null;

    public function __construct()
    {
        parent::__construct();

        $this->data["dark_mode"]               = config('dark_mode');
        $this->data["lang_id"]                 = config('lang_id');
        $this->getNotificationsHeader();

        if (!empty(session()->get("login_to_branch_id"))) {
            $this->user_id              = session()->get("login_to_branch_id_as_user_id");
            $this->data["current_user"] = User::findOrfail($this->user_id);
        }

        $this->current_user_data = $this->data["current_user"];

        if(is_object($this->current_user_data)){
            session()->put("this_user_id", $this->user_id);
            session()->put("this_user_type", $this->current_user_data->user_type);
        }


        if (
            is_object($this->current_user_data) &&
            $this->current_user_data->user_role != "admin"
        ){
            $this->branch_id                   = $this->current_user_data->branch_id;
            $this->branchData                  = branches_m::getBranchFromCache($this->branch_id);
        }

        if (getSettingsValue("default_timezone")!=null){
            date_default_timezone_set(getSettingsValue("default_timezone"));
        }

        if (is_object($this->current_user_data) && !empty($this->current_user_data->branch_id)){

            //branch
            $branchTimeZone = $this->branchData->branch_timezone;
            if (empty($branchTimeZone)){
                die("contact the developer E.234");
            }

            date_default_timezone_set($branchTimeZone);

        }


        \Session::put("current_branch_id", $this->branch_id);
        $this->data["current_branch_id"]   = $this->branch_id;
        $this->data["current_branch_data"] = $this->branchData;


        permissions::$user_id   = $this->user_id;
        $this->user_permissions = permissions::get_user_permissions();
    }

    public function returnMsgWithRedirection(Request $request, $redirectLink, $msg, $refresh = false)
    {

        if ($request->ajax() || $request->get("load_inner")) {
            return [
                "msg"           => $msg,
                "redirect"      => url($redirectLink),
                "refresh"       => $refresh,
                "refresh_after" => $refresh ? 3 : 0
            ];
        }
        else {
            $msg = "<div class='alert alert-info'>$msg</div>";
            return \redirect($redirectLink)->with("msg", $msg)->send();
        }
    }

    public function general_remove_item(Request $request, $model_name = "")
    {

        $output  = array();
        $item_id = (int)$request->get("item_id");

        if ($model_name == "") {
            $model_name = $request->get("table_name"); // App\User
        }

        if ($item_id > 0) {

            $model_name::destroy($item_id);

            $output       = array();
            $removed_item = $model_name::find($item_id);
            if (!isset($removed_item)) {
                $output["deleted"] = "yes";
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => $model_name,
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'delete',
                'old_obj'        => $removed_item,
            ]);

        }

        echo json_encode($output);

    }

    public function reorder_items(Request $request)
    {

        $items      = $request->get("items");
        $model_name = $request->get("table_name");  // App\User
        $field_name = $request->get("field_name");

        $output = array();

        if (is_array($items) && (count($items) > 0)) {
            foreach ($items as $key => $value) {
                $item_id    = $value[0];
                $item_order = $value[1];

                $returned_check = $model_name::find($item_id)->update([
                    "$field_name" => $item_order
                ]);

                if ($returned_check != true) {

                    $output["error"] = "error";
                    echo json_encode($output);
                    return;
                }

            }
            $output["success"] = "success";
        }
        else {
            $output["error"] = "bad array";
        }

        echo json_encode($output);
    }

    public function new_accept_item(Request $request, $model_name = "", $field_name = "")
    {

        $output  = array();
        $item_id = $request->get("item_id");

        if ($model_name == "") {
            $model_name = $request->get("table_name");
        }

        if ($field_name == "") {
            $field_name = $request->get("field_name");
        }

        $accept           = $request->get("accept");
        $item_primary_col = $request->get("item_primary_col");
        $accepters_data   = $request->get("acceptersdata");
        $accept_url       = $request->get("accept_url");
        $display_block    = $request->get("display_block");
        $func_after_edit  = $request->get("func_after_edit");


        if ($item_id > 0) {
            $obj            = $model_name::find($item_id);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => $model_name,
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'update',
                'old_obj'        => $obj,
            ]);

            $return_statues = $obj->update(["$field_name" => "$accept"]);

            $output["msg"] = generate_multi_accepters($accept_url, $obj, $item_primary_col, $field_name, $model_name, json_decode($accepters_data), $display_block, $func_after_edit);
        }


        echo json_encode($output);
    }

    public function general_self_edit(Request $request)
    {

        $output          = array();
        $item_id         = $request->get("item_id");
        $model_name      = $request->get("table_name");
        $field_name      = $request->get("field_name");
        $value           = $request->get("value");
        $input_type      = $request->get("input_type");
        $row_primary_col = $request->get("row_primary_col");
        $func_after_edit = $request->get("func_after_edit");
        $item_obj        = $model_name::find($item_id);

        if ($request->get("just_return_html") == "yes") {
            return generate_self_edit_input(
                $url = $request->get("url"),
                $item_obj,
                $item_primary_col = $row_primary_col,
                $item_edit_col = $field_name,
                $table = $model_name,
                $input_type = $input_type,
                "Click To Edit",
                $func_after_edit
            );
        }

        $output["success"] = "";
        $output["status"]  = "";

        if ($item_id > 0) {

            createLog($request, [
                'item_id'        => $item_id,
                'user_id'        => $this->user_id,
                'module'         => $model_name,
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'general-self-edit',
                'old_obj'        => $item_obj,
            ]);

            $return_statues = $item_obj->update(["$field_name" => $value]);
            if ($return_statues) {
                $output["success"] = "";
            }

            $output["msg"] = generate_self_edit_input(
                $url = $request->get("url"),
                $item_obj,
                $item_primary_col = $row_primary_col,
                $item_edit_col = $field_name,
                $table = $model_name,
                $input_type = $input_type,
                "Click To Edit",
                $func_after_edit
            );

        }
        else {
            $output["success"] = "error";
        }


        echo json_encode($output);
    }

    public function edit_slider_item(\Illuminate\Http\Request $request)
    {
        echo \App\btm_form_helpers\image::edit_slider_item_without_attachment($request);
    }

    public function setMetaTitle($title = "")
    {
        $this->data["meta_title"] .= " - ".$title;
    }

    public function getNotificationsHeader()
    {

        $cond   = [];
        $cond[] = ["notifications.user_id", "=", $this->user_id];

        $get_notifications = notification_m::get_notifications([
            'additional_and_wheres' => $cond,
            'order_by_col'          => "notifications.not_id",
            'order_by_type'         => "desc",
            'paginate'              => 5
        ]);


        $get_notifications                  = collect($get_notifications->all())->groupBy('notification_date')->all();
        $this->data['notifications_header'] = $get_notifications;
        $this->data['count_notifications']  = notification_m::where('is_seen', 0)->count();

    }

    public function save_push_token(Request $request)
    {



    }

    public function returnView(Request $request, $viewPath, $main_dir = "admin")
    {
        return parent::returnView($request, $viewPath, $main_dir);
    }

}
