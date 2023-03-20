<?php

namespace App\Http\Controllers\admin;

use App\form_builder\AdminsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\permissions\permission_pages_m;
use App\models\permissions\permissions_m;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends AdminBaseController
{

    use CrudTrait;

    /** @var User */
    public $modelClass;

    public function __construct()
    {

        parent::__construct();


        $this->setMetaTitle("Admins");

        $this->modelClass        = User::class;
        $this->viewSegment       = "admins";
        $this->routeSegment      = "admins";
        $this->builderObj        = new AdminsBuilder();
        $this->primaryKey        = "admin_id";
        $this->redirectAfterSave = url("/admin/admins");

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/admins", "show_action");

        $this->data["results"] = $this->modelClass::getAdmins([
            "order_by" => ["users.user_id", "asc"],
        ]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }


    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/admins", $item_id == null ? "add_action" : "edit_action");

    }

    private function _saving_validation($request, $user_id)
    {
        $this->data["success"] = "";

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["email"] = clean($request->get("email"));
        $rules_itself["email"] = "required|email|unique:users,email," . $user_id . ",user_id,deleted_at,NULL";

        return Validator::make($rules_values, $rules_itself, $attrs_names);

    }

    public function customValidation(Request $request, $item_id = null)
    {

        return $this->returnValidatorMsgs($this->_saving_validation($request, $item_id));

    }

    public function getEditObj(Request $request, $item_id)
    {

        $this->builderObj = new AdminsBuilder($item_id);

        $userObj = $this->modelClass::getAdmins([
            "free_conds" => [
                "users.user_id = {$item_id}"
            ],
            "return_obj" => "yes"
        ]);

        $userObj->allowed_lang_ids = json_decode($userObj->allowed_lang_ids);

        return $userObj;

    }

    public function beforeSaveRow(Request $request)
    {

        $request["provider"]  = "site";
        $request["user_role"] = "admin";

        if (isset($request["password"]) && !empty($request["password"])) {
            $request["password"]            = bcrypt($request["password"]);
            $request["password_changed_at"] = date("Y-m-d H:i:s");
        }
        elseif (isset($request["password"])) {
            unset($request["password"]);
        }

        return $request;
    }

    public function beforeAddNewRow(Request $request)
    {
        $request["user_type"]   = "admin";
        $request["user_enc_id"] = md5("#!@!#!*&*(&" . "sponsor_btm" . "#!@!#!*&*(&" . time() . random_bytes(5));

        return $request;
    }

    public function afterSave(Request $request, $item_obj)
    {

        if ($item_obj->user_id == $this->user_id && isset($request["password_changed_at"])) {
            $request->session()->put("password_changed_at", $request["password_changed_at"]);
        }

    }


}
