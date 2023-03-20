<?php

namespace App\Http\Controllers\admin;

use App\Events\InventoryLogs\AddInvalidEntryProduct;
use App\Http\Controllers\AdminBaseController;
use App\models\branch\branch_inventory_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_m;
use App\models\product\product_skus_m;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class InventoriesLogController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Inventory Log");
    }

    private function appendInventoryIdsForSpecificBranchAtReqData($requestData, Collection $allInventories){

        if ($this->branch_id != null){
            $requestData["inventory_ids"] = $allInventories->pluck("inv_id")->all();
        }

        return $requestData;
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/inventories_log", "show_logs");

        $this->data["all_inventories"] = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_products"]    = product_skus_m::getProductSkusWithVariantValues();
        $this->data["all_brands"]      = brands_m::getAllBrands();
        $this->data["all_cats"]        = categories_m::getSubCats();

        $this->data["request_data"]    = (object)$request->all();
        $requestData                   = $request->all();
        $requestData                   = $this->appendInventoryIdsForSpecificBranchAtReqData($requestData, $this->data["all_inventories"]);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data["results"]         = inventories_logs_m::getInventoriesLogs($requestData);

        return $this->returnView($request, "admin.subviews.inventories_log.show");

    }


    public function addInvalidEntryValidation(Request $request, $item_id)
    {

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["notes"]  = $request->get("notes");
        $rules_values["item_id"]  = $item_id;


        $rules_itself["item_id"]  = "required|numeric|exists:inventory_log,id";
        $rules_itself["notes"]    = "required";
        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);
        return $this->returnValidatorMsgs($validator);

    }


    public function addInvalidEntry(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/inventories_log", "add_invalid_entry");

        $this->data["item_id"] = $item_id;

        if ($request->method() == "POST") {

            $validator = $this->addInvalidEntryValidation($request, $item_id);
            if ($validator !== true) {
                return $validator;
            }

            $inv_log = inventories_logs_m::getInventoryLogByLogId($item_id);


            if ($inv_log->log_type != 'add_inventory'){

                return $this->returnErrorMessages($request, 'Cannot add invalid entry because log type of this log is not add product to inventory');
            }

            if ($inv_log->log_operation != 'increase'){
                return $this->returnErrorMessages($request, 'Cannot add invalid entry because log operation of this log is not increase');
            }

            if ($inv_log->is_refunded != 0){
                return $this->returnErrorMessages($request, 'Cannot add invalid entry because this log was previously entered as invalid');
            }

            $created_at_after_hour = $inv_log->created_at->addHour();

            if ($created_at_after_hour < now()){
                return $this->returnErrorMessages($request, 'An invalid entry cannot be added an hour after adding the product');
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Inventories-Log',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Invalid-Entry',
                'old_obj'        => $inv_log,
            ]);

            event(new AddInvalidEntryProduct(
                $item_id,
                $inv_log->inventory_id,
                $inv_log->pro_id,
                $inv_log->pro_sku_id,
                $inv_log->log_box_quantity,
                $inv_log->log_item_quantity,
                $request["notes"]
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/inventories-log/show-log",
                "Adding invalid entry successfully",
                true
            );

        }

        return $this->returnView($request, "admin.subviews.inventories_log.add_invalid_entry");
    }

}
