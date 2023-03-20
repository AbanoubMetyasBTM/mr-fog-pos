<?php

namespace App\Http\Controllers\admin;

use App\Events\InventoryProducts\AddBrokenProductToInventory;
use App\Events\InventoryProducts\AddProductToInventory;
use App\Events\InventoryProducts\MoveProductToInventory;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branch_inventory_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\inventory\inventories_m;
use App\models\inventory\inventories_products_m;
use App\models\product\product_skus_m;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;


class InventoryProductsController extends AdminBaseController
{

    use CrudTrait;

    /** @var inventories_products_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Inventory Products");

        $this->modelClass   = inventories_products_m::class;
        $this->viewSegment  = "inventories_products";
        $this->routeSegment = "inventories_products";
        $this->primaryKey   = "ip_id";
    }



    private function appendInventoryIdsForSpecificBranchAtReqData($requestData, Collection $allInventories){

        if ($this->branch_id != null){
            $requestData["inventory_ids"] = $allInventories->pluck("inv_id")->all();
        }

        return $requestData;
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/inventories_products", "show_inventories_products");

        $requestData                = $request->all();
        $this->data["request_data"] = (object)$requestData;

        $this->data["all_inventories"] = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_brands"]      = brands_m::getAllBrands();
        $this->data["all_cats"]        = categories_m::getSubCats();

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }


        $requestData           = $this->appendInventoryIdsForSpecificBranchAtReqData($requestData, $this->data["all_inventories"]);
        $this->data["results"] = inventories_products_m::getInventoriesProducts($requestData);


        return $this->returnView($request, "admin.subviews.inventories_products.show");
    }


    #region add product

    private function addProductToInventoryValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["inventory_id"]     = $request->get("inventory_id");
        $rules_values["pro_sku_id"]       = $request->get("pro_sku_id");
        $rules_values["ip_box_quantity"]  = $request->get("ip_box_quantity");
        $rules_values["ip_item_quantity"] = $request->get("ip_item_quantity");
        $rules_values["limit_quantity"]   = $request->get("ip_item_quantity");

        if ($rules_values["ip_box_quantity"] == "" && $rules_values["ip_item_quantity"] == "") {
            return [
                "error" => "please add item or box number"
            ];
        }

        $rules_itself["inventory_id"]     = "required|numeric|min:1";
        $rules_itself["pro_sku_id"]       = "required|numeric|min:1";
        $rules_itself["ip_box_quantity"]  = "numeric";
        $rules_itself["ip_item_quantity"] = "numeric";
        $rules_itself["limit_quantity"]   = "numeric";


        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function addProductToInventory(Request $request)
    {

        havePermissionOrRedirect("admin/inventories_products", "add_product");

        if ($this->branch_id != null){
            return abort(404);
        }

        $this->data["all_inventories"] = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_products"]    = product_skus_m::getProductSkusWithVariantValues();

        if ($request->method() == "POST") {

            $validator = $this->addProductToInventoryValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            $product_sku   = product_skus_m::getProductSkusWithVariantValues($request['pro_sku_id'])->first();
            $product_id    = $product_sku->pro_id;
            $invId         = $request->get("inventory_id");
            $proSkuId      = $request->get("pro_sku_id");
            $ipBoxQty      = $request->get("ip_box_quantity", 0);
            $ipItemQty     = $request->get("ip_item_quantity", 0);
            $limitItemsQty = $request->get("limit_quantity", 0);

            $this->checkIfSelectedInvenrotyIsAllowedToBeSelected($this->data["all_inventories"], $invId);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => "Inventory-Product",
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Product-To-Inventory',
            ]);

            event(new AddProductToInventory(
                $invId,
                $product_id,
                $proSkuId,
                (int)$ipBoxQty,
                (int)$ipItemQty,
                (int)$limitItemsQty,
                "add products to inventory"
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/inventories-products/show",
                "Your request is processing, you will be redirected after 2 seconds to see the updates",
                true
            );

        }


        return $this->returnView($request, "admin.subviews.inventories_products.add_product");

    }

    #endregion

    private function checkIfSelectedInvenrotyIsAllowedToBeSelected(Collection $allInventories, $selectedInventory){

        if ($this->branch_id==null){
            return true;
        }

        if (!in_array($selectedInventory, $allInventories->pluck("inv_id")->all())){
            abort(404);
            die();
        }

        return true;

    }

    #region move product

    private function moveProductFromInvToAnotherInvValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["from_inventory_id"] = $request->get("from_inventory_id");
        $rules_values["to_inventory_id"]   = $request->get("to_inventory_id");
        $rules_values["pro_sku_id"]        = $request->get("pro_sku_id");
        $rules_values["ip_box_quantity"]   = $request->get("ip_box_quantity");
        $rules_values["ip_item_quantity"]  = $request->get("ip_item_quantity");

        if ($rules_values["ip_box_quantity"] == "" && $rules_values["ip_item_quantity"] == "") {
            return [
                "error" => "please add item or box number"
            ];
        }

        $rules_itself["from_inventory_id"] = "required|numeric|min:1";
        $rules_itself["to_inventory_id"]   = "required|numeric|min:1|different:from_inventory_id";
        $rules_itself["pro_sku_id"]        = "required|numeric|min:1";
        $rules_itself["ip_box_quantity"]   = "numeric";
        $rules_itself["ip_item_quantity"]  = "numeric";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function moveProductFromInvToAnotherInv(Request $request)
    {

        havePermissionOrRedirect("admin/inventories_products", "move_product");

        $this->data["all_inventories"] = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_products"]    = product_skus_m::getProductSkusWithVariantValues();


        if ($request->method() == "POST") {

            $validator = $this->MoveProductFromInvToAnotherInvValidation($request);
            if ($validator !== true) {
                return $validator;
            }


            $product_sku = product_skus_m::getProductSkusWithVariantValues($request['pro_sku_id'])->first();
            $product_id  = $product_sku->pro_id;
            $fromInvId   = $request->get("from_inventory_id");
            $toInvId     = $request->get("to_inventory_id");
            $proSkuId    = $request->get("pro_sku_id");
            $ipBoxQty    = $request->get("ip_box_quantity", 0);
            $ipItemQty   = $request->get("ip_item_quantity", 0);

            $this->checkIfSelectedInvenrotyIsAllowedToBeSelected($this->data["all_inventories"], $fromInvId);
            $this->checkIfSelectedInvenrotyIsAllowedToBeSelected($this->data["all_inventories"], $toInvId);

            $fromInv = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($fromInvId, $proSkuId);


            if (!is_object($fromInv)) {
                return $this->returnErrorMessages($request, 'This inventory not has this product');
            }

            if ($ipBoxQty > $fromInv->ip_box_quantity) {
                return $this->returnErrorMessages($request, 'The quantity of this product boxes required to move is greater than the quantity in inventory');
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Inventory-Products',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'move-Product-From-Inv-To-Another-Inv',
                'old_obj'        => $fromInv,
            ]);

            event(new MoveProductToInventory(
                $fromInvId,
                $toInvId,
                $product_id,
                $proSkuId,
                (int)$ipBoxQty,
                (int)$ipItemQty
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/inventories-products/show",
                "Your request is processing, you will be redirected after 2 seconds to see the updates",
                true
            );

        }

        return $this->returnView($request, "admin.subviews.inventories_products.move_product");
    }

    #endregion

    #region add broken products

    private function addBrokenProductToInventoryValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["inventory_id"]     = $request->get("inventory_id");
        $rules_values["pro_sku_id"]       = $request->get("pro_sku_id");
        $rules_values["ip_box_quantity"]  = $request->get("ip_box_quantity");
        $rules_values["ip_item_quantity"] = $request->get("ip_item_quantity");

        if ($rules_values["ip_box_quantity"] == "" && $rules_values["ip_item_quantity"] == "") {
            return [
                "error" => "please add item or box number"
            ];
        }

        $rules_itself["inventory_id"]     = "required|numeric|min:1";
        $rules_itself["pro_sku_id"]       = "required|numeric|min:1";
        $rules_itself["ip_box_quantity"]  = "numeric";
        $rules_itself["ip_item_quantity"] = "numeric";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function addBrokenProductToInventory(Request $request)
    {

        havePermissionOrRedirect("admin/inventories_products", "add_broken_product");

        $this->data["all_inventories"] = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_products"]    = product_skus_m::getProductSkusWithVariantValues();

        if ($request->method() == "POST") {

            $validator = $this->addBrokenProductToInventoryValidation($request);
            if ($validator !== true) {
                return $validator;
            }
            $product_sku = product_skus_m::getProductSkusWithVariantValues($request['pro_sku_id'])->first();
            $product_id  = $product_sku->pro_id;
            $invId       = $request->get("inventory_id");
            $proSkuId    = $request->get("pro_sku_id");
            $ipBoxQty    = $request->get("ip_box_quantity", 0);
            $ipItemQty   = $request->get("ip_item_quantity", 0);

            $this->checkIfSelectedInvenrotyIsAllowedToBeSelected($this->data["all_inventories"], $invId);

            $inv = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($invId, $proSkuId);


            if (!is_object($inv)) {
                return $this->returnErrorMessages($request, 'This inventory not has this product');
            }

            if ($ipBoxQty > $inv->ip_box_quantity) {
                return $this->returnErrorMessages($request, 'The quantity of this product boxes required is greater than the quantity in inventory');
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Inventory-Products',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Broken-Product-To-Inventory',
                'old_obj'        => $inv,
            ]);

            event(new AddBrokenProductToInventory(
                $invId,
                $product_id,
                $proSkuId,
                (int)$ipBoxQty,
                (int)$ipItemQty
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/inventories-products/show",
                "Your request is processing, you will be redirected after 2 seconds to see the updates",
                true
            );

        }

        return $this->returnView($request, "admin.subviews.inventories_products.add_broken_product");

    }

    #endregions

    public function updateQuantityLimit(Request $request)
    {

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["limit_quantity"] = $request->get("value");
        $rules_itself["limit_quantity"] = "numeric|min:0";

        $validator = $this->returnValidatorMsgs(Validator::make($rules_values, $rules_itself, $attrs_names));

        if ($validator !== true) {
            return json_encode($validator);
        }

        return $this->general_self_edit($request);

    }


}
