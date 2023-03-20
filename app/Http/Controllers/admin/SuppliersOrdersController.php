<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\AddSupplierOrderHelper;
use App\btm_form_helpers\image;
use App\btm_form_helpers\WalletHelper;
use App\Http\Controllers\AdminBaseController;
use App\Jobs\supplierOrders\AddOrderJob;
use App\Jobs\supplierOrders\AddSupplierOrderJob;
use App\Jobs\supplierOrders\MakeOrderDoneJob;
use App\Jobs\supplierOrders\MakeSupplierOrderDoneJob;
use App\Jobs\supplierOrders\ReturnSupplierOrderItemsJob;
use App\models\branch\branches_m;
use App\models\inventory\inventories_m;
use App\models\product\product_skus_m;
use App\models\supplier\supplier_order_items_m;
use App\models\supplier\suppliers_m;
use App\models\supplier\suppliers_orders_m;
use App\models\wallets_m;
use App\User;
use Illuminate\Http\Request;

class SuppliersOrdersController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/suppliers_orders", "show_action");

        $this->data["suppliers"]     = suppliers_m::getAllSuppliers();
        $this->data["all_employees"] = User::getAllUsersWithType('employee');
        $this->data["branches"]      = branches_m::getAllBranches();
        $this->data["request_data"]  = (object)$request;


        $conds                       = $request->all();
        $conds['paginate']           = 50;
        $this->data["results"]       = suppliers_orders_m::getAllSuppliersOrders(null, $conds);

        return $this->returnView($request, "admin.subviews.suppliers_orders.show");

    }

    public function showOrder(Request $request, $orderId)
    {

        $orderData                            = suppliers_orders_m::getAllSuppliersOrders($orderId);
        $supplierData                         = suppliers_m::getSupplierDataById($orderData->supplier_id);
        $this->data['wallet_obj_of_supplier'] = wallets_m::getWalletById($supplierData->wallet_id);

        if (!is_object($orderData)) {
            return abort(404);
        }

        $this->data["order"] = $orderData;

        $this->data["items"] = collect(supplier_order_items_m::getOrderItemsByOrderId($orderId));


        foreach ($this->data["items"] as $item) {
            if ($item->operation_type == "buy") {
                $item->returned_qty =
                    collect($this->data["items"])->where("pro_sku_id", "=", $item->pro_sku_id)->
                    where("item_type", "=", $item->item_type)->
                    where("operation_type", "=", "return")->
                    pluck("order_quantity")->sum();
            }
            else {
                $item->returned_qty = "------";
            }
        }

        return $this->returnView($request, "admin.subviews.suppliers_orders.show_order");
    }

    public function checkProductPrice(Request $request)
    {

        // get pro_sku data
        $productSku = collect(product_skus_m::getProductSkusWithVariantValues($request->get("pro_sku_id")))->first();

        $productSku->item_type       = $request->get("item_type");
        $productSku->pro_sku_id      = $productSku->ps_id;
        $productSku->item_total_cost = $request->get("item_cost");


        $orderItem[] = $productSku;

        $productsPrices = AddSupplierOrderHelper::checkIfPriceOfSupplierProductGreaterThanMainProductPrice($orderItem);

        $branchesPrices = AddSupplierOrderHelper::checkIfPriceOfSupplierProductGreaterThanBranchesPrices($orderItem);

        $msg = "";
        if (!empty($productsPrices)) {
            $msg .= "Please edit this products prices $productsPrices";
        }

        if (!empty($branchesPrices)) {
            $msg .= "<br>Please edit this branches products prices $branchesPrices";
        }


        return $msg;

    }

    public function addOrderValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["product_sku"] = $request->get("product_sku");

        $rules_itself["product_sku"] = "required|min:1";
        $validator                   = \Validator::make($rules_values, $rules_itself, $attrs_names);
        return $this->returnValidatorMsgs($validator);
    }

    public function addOrder(Request $request)
    {

        havePermissionOrRedirect("admin/suppliers_orders", "add_action");

        $this->data["suppliers"]   = suppliers_m::getAllSuppliers();
        $this->data["employees"]   = User::getAllUsersWithType('employee');
        $this->data["branches"]    = branches_m::getAllBranches();
        $this->data["inventories"] = inventories_m::getAllInventories();


        if ($request->method() == "POST") {

            $validator = $this->addOrderValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            $requestData = $request->all();

            $requestData["ordered_at"]  = date("Y-m-d", strtotime($request->get("ordered_at")));
            $requestData["employee_id"] = $this->user_id;

            if ($request->has("order_img_obj")) {
                $imgObj = image::general_save_img_without_attachment($request, [
                    "img_file_name" => "order_img_obj",
                ]);

                $requestData["original_order_img_obj"] = json_encode($imgObj);
            }
            $orderCost                       = AddSupplierOrderHelper::orderCostHandler($request);
            $requestData["total_items_cost"] = $orderCost["total_items_cost"];
            $requestData["total_cost"]       = $orderCost["total_cost"];


            if (floatval($request->get('paid_amount')) > $orderCost["items_cost"]) {
                return $this->returnErrorMessages(
                    $request,
                    "The amount to be paid should be less than the cost of the order: ".$orderCost["items_cost"]
                );
            }

            dispatch(new AddSupplierOrderJob([
                'requestData'       => $requestData
            ]));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/suppliers-orders",
                AddSupplierOrderHelper::checkAllItemsPrices($request),
                true
            );

        }


        return $this->returnView($request, "admin.subviews.suppliers_orders.add_order");
    }

    public function makeOrderDone(Request $request, $orderId)
    {

        // get order data
        $orderData = suppliers_orders_m::getAllSuppliersOrders($orderId);
        if (!is_object($orderData)) {
            return abort(404);
        }

        // get order items
        $orderItems          = supplier_order_items_m::getOrderItemsByOrderId($orderId);

        $this->data['order'] = $orderData;
        $this->data['items'] = $orderItems;

        $request->request->add([
            "supplier_id"      => $orderData->supplier_id
        ]);

        if ($request->method() == 'POST'){

            $orderItems= Collect($orderItems)->toArray();

            // after make order done

            dispatch(new MakeSupplierOrderDoneJob([
                'orderId'     => $orderId,
                'requestData' => $request->all(),
            ]));

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Suppliers-Orders',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'make-Order-Done',
            ]);


            return $this->returnMsgWithRedirection(
                $request,
                "admin/suppliers-orders/show-order/$orderId",
                "Order done successfully",
                true
            );

        }


        return $this->returnView($request, "admin.subviews.suppliers_orders.make_order_done");
    }

    public function returnOrderItemsValidation(Request $request, $supplierData, $returnAmount)
    {

        $walletObjOfSupplier = wallets_m::getWalletById($supplierData->wallet_id);

        if ($request->get('received_amount') > $returnAmount){
            return [
                "error" =>
                    "It is not possible to take an amount of more than {$returnAmount} cash"
            ];
        }

        if (
            $walletObjOfSupplier->wallet_amount > $returnAmount &&
            $request->get('received_amount')>0
        ){
            return [
                "error" =>
                    "It is not possible to take any money cash supplier has credit card $walletObjOfSupplier->wallet_amount"
            ];
        }


        //TODO check mets
        if
        (
            (
                $returnAmount >= $walletObjOfSupplier->wallet_amount &&
                $request->get('received_amount')>0
            ) &&
            (
                $request->get('received_amount') > $returnAmount-$walletObjOfSupplier->wallet_amount
            )
        ){
            {
                return [
                    "error" =>
                        "It is not possible to take an amount of more than ".($returnAmount-$walletObjOfSupplier->wallet_amount) ." cash"
                ];
            }

        }


        return true;

    }

    public function returnOrderItems(Request $request, $orderId)
    {

        // get return items data
        $allItemsData = collect(supplier_order_items_m::getOrderItemsByOrderId($orderId));
        $orderData    = suppliers_orders_m::getOrderById($orderId);
        $supplierData = suppliers_m::getSupplierDataById($orderData->supplier_id);

        $returnAmount = AddSupplierOrderHelper::calculateTotalItemsCostReturnOrderItems($request,$allItemsData);

        $validation = $this->returnOrderItemsValidation($request, $supplierData, $returnAmount);
        if ($validation !== true) {
            return $validation;
        }


        $checkError = AddSupplierOrderHelper::returnOrderItemsQuantityValidation($request, $allItemsData, $orderData, $supplierData);

        if(isset($checkError["error"])){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/suppliers-orders/show-order/$orderId",
                $checkError["error"]
            );
        }

        dispatch(new ReturnSupplierOrderItemsJob([
            'request_data'  => $request->all(),
            'order_id'      => $orderId,
            'return_amount' => $returnAmount,
        ]));

        createLog($request, [
            'item_id'        => $orderId,
            'user_id'        => $this->user_id,
            'module'         => 'Suppliers-Orders',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'return-Order-Items'
        ]);

        return $this->returnMsgWithRedirection(
            $request,
            "admin/suppliers-orders/show-order/$orderId",
            "Items returned successfully",
            true
        );
    }

    public function cancelOrder(Request $request, $orderId)
    {

        $orderObject=suppliers_orders_m::getOrderById($orderId);

        if ($orderObject->order_status!='pending'){
            return [
                "error"=>"can not change status to canceled unless the current status is pending"
            ];
        }

        suppliers_orders_m::changeOrderStatus($orderId, "canceled");

        createLog($request, [
            'item_id'        => $orderId,
            'user_id'        => $this->user_id,
            'module'         => 'Suppliers-Orders',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'cancel-Order'
        ]);

        return $this->returnMsgWithRedirection(
            $request,
            "admin/suppliers-orders/show-order/$orderId",
            "Order cancelled successfully"
        );
    }

}
