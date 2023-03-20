<?php

namespace App\Http\Controllers\admin;

use App\form_builder\CouponsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\client\clients_m;
use App\models\coupon\coupons_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class CouponsController extends AdminBaseController
{

    use CrudTrait;

    /** @var coupons_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Coupons");

        $this->modelClass          = coupons_m::class;
        $this->viewSegment         = "coupons";
        $this->routeSegment        = "coupons";
        $this->builderObj          = new CouponsBuilder();
        $this->primaryKey          = "coupon_id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/coupons", "show_action");

        $coupons               = $this->modelClass::getAllCouponsOrSpecificBranch();
        $this->data["results"] = $coupons;

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }


    public function getEditObj(Request $request, $item_id)
    {

        $itemObj = coupons_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }

        return $itemObj;

    }

    public function beforeAddNewRow(Request $request){

        if ($this->branch_id != null) {
            $request["branch_id"] = $this->branch_id;
        }

        return $request;
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/coupons", $item_id == null ? "add_action" : "edit_action");
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/coupons", "delete_action");

        $item_id = $request->get('item_id');
        $itemObj = coupons_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }

    }


}
