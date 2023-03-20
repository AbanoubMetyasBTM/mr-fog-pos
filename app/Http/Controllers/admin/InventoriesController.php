<?php

namespace App\Http\Controllers\admin;

use App\form_builder\InventoriesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\inventory\inventories_m;
use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_products_m;
use Illuminate\Http\Request;

class InventoriesController extends AdminBaseController
{

    use CrudTrait;

    /** @var inventories_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Inventories");

        $this->modelClass          = inventories_m::class;
        $this->viewSegment         = "inventories";
        $this->routeSegment        = "inventories";
        $this->builderObj          = new InventoriesBuilder();
        $this->primaryKey          = "inv_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/inventories", "show_action");

        $this->data["results"] = $this->modelClass::getAllInventories();

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/inventories", $item_id == null ? "add_action" : "edit_action");

    }


    public function beforeDeleteRow(Request $request)
    {

        havePermissionOrRedirect("admin/inventories", "delete_action");

    }


    public function delete(Request $request){

        $this->beforeDeleteRow($request);

        $inventory_id              = (int)$request->get("item_id");
        $IsSetProductsForInventory = inventories_products_m::checkIfInventoryHasProducts($inventory_id);
        $IsSetLogsForInventory     = inventories_logs_m::checkIfInventoryHasLogs($inventory_id);
        if ($IsSetProductsForInventory == false && $IsSetLogsForInventory == false){

            $this->general_remove_item($request, $this->modelClass);
        }
        else{
            $output["msg"] = "can not delete this inventory because it contains products";
            return json_encode($output);
        }

    }
}
