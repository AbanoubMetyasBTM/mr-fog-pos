<?php

namespace App\Http\Controllers\admin;

use App\form_builder\BranchInventoryBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branch_inventory_m;
use App\models\branch\branches_m;
use App\models\inventory\inventories_m;
use Illuminate\Http\Request;

class BranchesInventoriesController extends AdminBaseController
{

    use CrudTrait;

    /** @var branch_inventory_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Branches");

        $this->modelClass          = branch_inventory_m::class;
        $this->viewSegment         = "branches_inventories";
        $this->routeSegment        = "branches-inventories";
        $this->builderObj          = new BranchInventoryBuilder();
        $this->primaryKey          = "id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/branches_inventories", "show_action");

        $this->data["request_data"] = (object)$request->all();
        $this->data["results"]      = $this->modelClass::getAllBranchesInventories($request->all());

        $this->data['branches']     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data['inventories']  = inventories_m::getData();
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/branches_inventories", $item_id == null ? "add_action" : "edit_action");
    }


    public function customValidation(Request $request, $item_id = null)
    {
        $attr['branch_id']    = $request['branch_id'];
        $attr['inventory_id'] = $request['inventory_id'];
        if (isset($request['is_main_inventory'])){
            $attr['is_main_inventory'] = 1;
        }


        if (is_null($item_id)){

            $branchInv = branch_inventory_m::getAllBranchesInventories($attr);

            if (count($branchInv)){
                return [
                    "error" => "This inventory is already added to this branch"
                ];
            }


        }
        else{
            if (isset($request['is_main_inventory'])){

                $branchInv = branch_inventory_m::getMainInvBranchesExceptSpecificBranchInv($attr['branch_id'], $attr['inventory_id']);
                if (count($branchInv)){
                    return [
                        "error" => "This inventory is main for another branch"
                    ];
                }
            }
        }

        return true;
    }

    public function beforeSaveRow(Request $request)
    {

        if ($this->branch_id != null) {
            $request["branch_id"] = $this->branch_id;
        }

        return $request;

    }

    public function beforeDeleteRow(Request $request)
    {

        havePermissionOrRedirect("admin/branches_inventories", "delete_action");

    }


}
