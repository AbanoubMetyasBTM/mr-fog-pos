<?php

namespace App\Http\Controllers\admin;

use App\form_builder\RegistersBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\client\clients_m;
use App\models\client\clients_orders_m;
use App\models\coupon\coupons_m;
use App\models\register\registers_m;
use App\models\register\registers_sessions_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class RegistersController extends AdminBaseController
{

    use CrudTrait;

    /** @var registers_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Registers");

        $this->modelClass          = registers_m::class;
        $this->viewSegment         = "registers";
        $this->routeSegment        = "registers";
        $this->builderObj          = new RegistersBuilder();
        $this->primaryKey          = "register_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/registers", "show_action");

        $this->data["results"]                      = $this->modelClass::getAllRegisterOrSpecificBranch();
        $this->data["registers_sessions"]           = registers_sessions_m::getAllNotEndedRegistersSessions();
        $this->data["registers_have_open_sessions"] = registers_sessions_m::getAllNotEndedRegistersSessions()->
                                                      pluck('register_id')->all();
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function getEditObj(Request $request, $item_id)
    {
        $itemObj = registers_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!= $this->branch_id){
            abort(404);
            die();
        }

        return $itemObj;
    }

    public function beforeAddNewRow(Request $request)
    {
        if ($this->branch_id != null) {
            $request["branch_id"] = $this->branch_id;
        }
        return $request;
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/registers", $item_id == null ? "add_action" : "edit_action");
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/registers", "delete_action");
        $item_id = $request->get('item_id');
        $itemObj = registers_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }
    }


    public function delete(Request $request){

        $this->beforeDeleteRow($request);

        $register_id              = (int)$request->get("item_id");
        $IsSetOrdersForRegister   = clients_orders_m::checkIfRegisterHasOrders($register_id);
        $IsSetSessionsForRegister = registers_sessions_m::checkIfRegisterHasSessions($register_id);


        if (!($IsSetOrdersForRegister == false && $IsSetSessionsForRegister == false)){
            return json_encode([
                "msg" => "can not delete this register because it has orders",
            ]);
        }

        $this->general_remove_item($request, $this->modelClass);

    }
}
