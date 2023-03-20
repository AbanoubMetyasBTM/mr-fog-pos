<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\AddOrderHelper;
use App\btm_form_helpers\InventoryHelper;
use App\btm_form_helpers\ReturnClientOrderItemsHelper;
use App\Events\InventoryProducts\AddProductToInventory;
use App\Events\Wallets\DepositMoneyForWallet;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\RegisterSessionLogTrait;
use App\Jobs\orders\AddOrderJob;
use App\Jobs\orders\MakeOrderDoneJob;
use App\models\branch\branch_inventory_m;
use App\models\branch\branch_prices_m;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\client\clients_order_items_m;
use App\models\client\clients_orders_m;
use App\models\gift_card\gift_cards_m;
use App\models\loyalty_points_to_money_m;
use App\models\product\product_skus_m;
use App\models\wallets_m;
use App\User;
use Illuminate\Http\Request;

class ClientsOrdersController extends AdminBaseController
{
    use RegisterSessionLogTrait;


    public function __construct()
    {
        parent::__construct();
        $this->setMetaTitle("Clients Orders");
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/clients_orders", "show_action");

        $conds             = $request->all();
        $conds['paginate'] = 50;
        if ($this->branch_id != null) {
            $conds["branch_id"] = $this->branch_id;
        }

        if(!empty($request->get("client_id"))){
            $this->data["client_obj"] = clients_m::getClientDataById($request->get("client_id"));
        }

        $this->data["results"]       = clients_orders_m::getAllClientOrders(null, $conds);
        $this->data["clients"]       = clients_m::getAllClients();
        $this->data["all_employees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');
        $this->data["branches"]      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["request_data"]  = (object)$request->all();

        return $this->returnView($request, "admin.subviews.clients_orders.show");
    }


    private function showOrderDataWithItemsBasedOnView(Request  $request, $orderId, $view)
    {

        $this->data["order"]     = clients_orders_m::getAllClientOrders($orderId);
        $this->data["items"]     = collect(clients_order_items_m::getOrderItemsByOrderId($orderId));
        $clientObj               = clients_m::getClientDataById($this->data["order"]->client_id);
        $this->data["clientObj"] = [
            "id"                  => $clientObj->client_id,
            "display_text"        => $clientObj->client_name,
            "client_type"         => $clientObj->client_type,
            "wallet_amount"       => $clientObj->wallet_amount,
            "points_wallet_value" => $clientObj->points_wallet_value,
        ];


        foreach ($this->data["items"] as $item) {

            if ($item->operation_type == "buy") {
                $item->returned_qty =
                    collect($this->data["items"])->
                    where("pro_sku_id", "=", $item->pro_sku_id)->
                    where("item_type", "=", $item->item_type)->
                    where("operation_type", "=", "return")->
                    pluck("order_quantity")->sum();
            }
            else {
                $item->returned_qty = "------";
            }
        }

        return $this->returnView($request, "admin.subviews.clients_orders.$view");

    }


    public function showOrder(Request $request, $orderId)
    {
        return $this->showOrderDataWithItemsBasedOnView($request, $orderId, 'show_order');
    }

    public function printOrderInvoice(Request $request, $orderId)
    {
        return $this->showOrderDataWithItemsBasedOnView($request, $orderId, 'print_order_invoice');
    }

    #region add order and make order done
    public function addOrderValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];


        // check if items available in branch
        $branchId                    = $request->session()->get('current_branch_id');
        $productsSkuIds              = $request->get("product_sku");
        $branchProductsPricesIds     = collect(branch_prices_m::getPricesByBranchId($branchId))->pluck("sku_id")->toArray();
        $productsAvailableInBranch   = array_intersect($productsSkuIds, $branchProductsPricesIds);
        $productNotAvailableInBranch = collect(array_diff($productsSkuIds, $productsAvailableInBranch))->unique()->toArray();

        if(!empty($productNotAvailableInBranch)){
            $productsNotAvailableInBranchData  = collect(product_skus_m::getProductsSkusWithVariantValuesByIds($productNotAvailableInBranch));
            $productsNamesNotAvailableInBranch = implode('<br>', $productsNotAvailableInBranchData->pluck('product_name')->toArray());
            return [
                "error" => "This products not available at the branch <br> $productsNamesNotAvailableInBranch",
            ];
        }

        // check wallet value
        if($request->get('wallet_paid_amount') != ''){
            $result = $this->checkIfClientWalletIsUsable($request);

            if (isset($result['error'])){
                return $result;
            }
        }

        $coupon = $this->checkIfCouponIsUsable($request);
        if (isset($coupon["error"])){
            return $coupon["error"];
        }


        // check gift card
        $giftCardWallet = 0;
        if($request->get('gift_card') != ''){
            $result = $this->checkIfGiftCardIsUsable($request);

            if (isset($result['error'])){
                return $result;
            }

            if (isset($result['data'])){
                $giftCardWallet = $result['data'];
            }

        }

        $clientObj = clients_m::getClientDataById($request->get("client_id"));
        $userPaid = (
            floatval($request->get("cash_paid_amount")) +
            floatval($request->get("debit_card_paid_amount")) +
            floatval($request->get("credit_card_paid_amount")) +
            floatval($request->get("cheque_paid_amount")) +
            floatval($giftCardWallet)
        );

        $total_cost = floatval($request->get("total_cost"));

        if (
            $request->get("order_status") == "done" &&
            $clientObj->client_type=="retailer" &&
            $total_cost != $userPaid
        ){
            return [
                "error" => "you should pay ".$request->get("total_cost")." instead of ".$userPaid
            ];
        }

        $rules_values["client_id"]               = $request->get("client_id");
        $rules_values["order_status"]            = $request->get("order_status");
        $rules_values["order_quantity"]          = array_map('intval', $request->get("order_quantity"));
        $rules_values["cash_paid_amount"]        = $request->get("cash_paid_amount");
        $rules_values["debit_card_paid_amount"]  = $request->get("debit_card_paid_amount");
        $rules_values["credit_card_paid_amount"] = $request->get("credit_card_paid_amount");
        $rules_values["cheque_paid_amount"]      = $request->get("cheque_paid_amount");


        if ($rules_values["order_status"] == 'pick_up'){
            $rules_values["pick_up_date"] = $request->get("pick_up_date");
            $rules_itself["pick_up_date"] = "required|date|after:yesterday";
        }


        $rules_itself["client_id"]               = "required|exists:clients,client_id";
        $rules_itself["order_status"]            = "required";
        $rules_itself["order_quantity.*"]        = "required|numeric|min:1";
        $rules_itself["cash_paid_amount"]        = "numeric|min:1";
        $rules_itself["debit_card_paid_amount"]  = "numeric|min:1";
        $rules_itself["credit_card_paid_amount"] = "numeric|min:1";
        $rules_itself["cheque_paid_amount"]      = "numeric|min:1";

        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);

    }

    private function checksBeforeAddOrderOrMakeOrderDone(Request $request)
    {


        if ($this->current_user_data->user_role == "admin") {
            return $this->returnMsgWithRedirection(
                $request,
                "admin/dashboard",
                "you can not add order, login as employee"
            );
        }

        // check if is set branch id
        $branchId  = $request->session()->get('current_branch_id');
        if (is_null($branchId)){
            return $this->returnMsgWithRedirection(
                $request,
                'admin',
                'You must choose the branch first'
            );
        }

        // check if employee start register session
        if ($this->checkIsSetRegisterSessionIdInSession() === false){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'You must start the session on your register first'
            );
        }

        //check if this branch connect with inventories or not
        $mainBranch = branch_inventory_m::getMainInvOfBranch($branchId);
        if (!is_object($mainBranch)){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/dashboard',
                'contact with main admin to attach your branch with main inventory'
            );
        }

        return true;

    }

    public function addOrder(Request $request)
    {
        havePermissionOrRedirect("admin/clients_orders", "add_action");
        $check = $this->checksBeforeAddOrderOrMakeOrderDone($request);
        if ($check !== true) {
            return $check;
        }

        $this->data["all_points_redeems"] = loyalty_points_to_money_m::getByCurrency($this->branchData->branch_currency);


        if ($request->method() == "POST") {

            $validator = $this->addOrderValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            dispatch(new AddOrderJob([
                "request"             => $request->all(),
                "current_branch_id"   => $request->session()->get('current_branch_id'),
                "register_id"         => $request->session()->get('register_id'),
                "register_session_id" => $request->session()->get('register_session_id'),
                "employee_id"         => $this->user_id,
            ]));

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Client-Orders',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Order',
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/clients-orders",
                "Order is processing now, You'll be redirected after 2 Seconds",
                true
            );
        }

        return $this->returnView($request, "admin.subviews.clients_orders.add_order");
    }


    public function makeOrderDoneValidation(Request $request, $orderData, $orderItemsData)
    {
        // check if items available in branch
        $productsSkuIds              = $orderItemsData->pluck("pro_sku_id")->toArray();
        $branchProductsPricesIds     = collect(branch_prices_m::getPricesByBranchId($orderData->branch_id))->pluck("sku_id")->toArray();
        $productsAvailableInBranch   = array_intersect($productsSkuIds, $branchProductsPricesIds);
        $productNotAvailableInBranch = collect(array_diff($productsSkuIds, $productsAvailableInBranch))->unique()->toArray();

        if(!empty($productNotAvailableInBranch)){
            $productsNotAvailableInBranchData  = collect(product_skus_m::getProductsSkusWithVariantValuesByIds($productNotAvailableInBranch));
            $productsNamesNotAvailableInBranch = implode('<br>', $productsNotAvailableInBranchData->pluck('product_name')->toArray());
            return [
                "error" => "This products not available at the branch <br> $productsNamesNotAvailableInBranch",
            ];
        }

        // check qty of product inventory after buy
        $productsNotAvailableInMainInv = AddOrderHelper::checkIfQtyItemsAvailableInMainInventoryOfBranch($orderItemsData, $orderData->branch_id);
        if ($productsNotAvailableInMainInv !== true){
            return $productsNotAvailableInMainInv;
        }

        $coupon = $this->checkIfCouponIsUsable($request);
        if (isset($coupon["error"])){
            return $coupon["error"];
        }

        // check gift card
        if($request->get('gift_card') != ''){
            $result = $this->checkIfGiftCardIsUsable($request);

            if (isset($result['error'])){
                return $result;
            }
        }

        // check wallet value
        if($request->get('wallet_paid_amount') != ''){
            $result = $this->checkIfClientWalletIsUsable($request);

            if (isset($result['error'])){
                return $result;
            }
        }

        $orderItemsData = ["data" => $orderItemsData->toArray()];
        $orderData      = $orderData->toArray();

        if (!empty($request->get('coupon_code'))) {
            $applyCouponData = AddOrderHelper::applyCoupon($request, $request->session()->get('current_branch_id'), $orderItemsData);
            $orderItemsData  = $applyCouponData["orderItemsData"] ?? $orderItemsData;
            $orderData       = array_merge($orderData, $applyCouponData["mergeData"]);
        }

        if (!empty($request->get("selected_redeem"))){
            $applyRedeemData = AddOrderHelper::applyRedeem($request, $request->session()->get('current_branch_id'), $orderItemsData);
            $orderItemsData  = $applyRedeemData["orderItemsData"] ?? $orderItemsData;
            $orderData       = array_merge($orderData, $applyRedeemData["mergeData"]);
        }

        $checkAmountPaid = AddOrderHelper::checkAmountPaidBasedOnClientType($request, $orderData["total_cost"]);
        if ($checkAmountPaid !== true) {
            return $checkAmountPaid;
        }

    }

    public function makeOrderDone(Request $request, $orderId)
    {
        havePermissionOrRedirect("admin/clients_orders", "add_action");
        $check = $this->checksBeforeAddOrderOrMakeOrderDone($request);
        if ($check !== true) {
            return $check;
        }

        $this->data["all_points_redeems"] = loyalty_points_to_money_m::getByCurrency($this->branchData->branch_currency);

        if ($request->method() == 'POST'){

            // get order data
            $orderData  = clients_orders_m::getAllClientOrders($orderId);
            // get order items data
            $orderItems = collect(clients_order_items_m::getOrderItemsByOrderId($orderId));
            $validation = $this->makeOrderDoneValidation($request, $orderData, $orderItems);

            if (!empty($validation)){
                return $validation;
            }

            dispatch(new MakeOrderDoneJob([
                "orderId"               => $orderId,
                "request"               => $request->all(),
                "current_branch_id"     => $request->session()->get('current_branch_id'),
                "register_id"           => $request->session()->get('register_id'),
                "register_session_id"   => $request->session()->get('register_session_id'),
                "employee_id"           => $this->user_id,
            ]));

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Clients-Orders',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'make-Order-Done',
                'old_obj'        => $orderData
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/clients-orders",
                "Order is processing now",
                true
            );
        }

        return $this->showOrderDataWithItemsBasedOnView($request, $orderId, 'make_order_done');
    }

    #endregion


    public function checkIfCouponIsUsable(Request $request)
    {
        return AddOrderHelper::checkIfCouponIsUsable($request, $request->session()->get('current_branch_id'));
    }

    public function checkIfGiftCardIsUsable(Request $request)
    {
        $data = [];
        $card = gift_cards_m::getGiftCardByUniqueNumber($request->get("gift_card"));

        if (is_null($card)){
            $data["error"] = "This Gift Card is invalid or has expired";
        }
        else{
            $data["data"] = $card->wallet_amount;
        }

        return $data;
    }

    public function checkIfClientWalletIsUsable(Request $request)
    {
        $client       = clients_m::getClientDataById($request->get('client_id'));
        $result       = [];

        if (floatval($client->wallet_amount) < 0){
            return [
                "error" => "You can not use wallet because you have minus balance"
            ];
        }

        if (floatval($client->wallet_amount) < floatval($request->get('wallet_paid_amount'))){
            return [
                "error" => "The available amount in the wallet is $client->wallet_amount"
            ];
        }

        $result['data'] = $client->wallet_amount;

        return $result;
    }




    #region return order items
    public function returnOrderItemsValidation(Request $request, $orderData, $orderItemsData)
    {
        // check if items will return is correct
        $checkIfItemsQtyWillReturnIsCorrect = ReturnClientOrderItemsHelper::checkIfItemsQtyWillReturnIsCorrect($request, $orderItemsData);
        if (!empty($checkIfItemsQtyWillReturnIsCorrect)){
            return [
                'error' => $checkIfItemsQtyWillReturnIsCorrect,
            ];
        }

        // check if client has dept or not
        $checkIfClientCanReturnMoney = ReturnClientOrderItemsHelper::checkIfClientCanReturnMoney($request, $orderData->client_id);
        if(!empty($checkIfClientCanReturnMoney)){
            return $checkIfClientCanReturnMoney;
        }

        // check if order does not contain a gift card can not return amount by gift card
        if(floatval($request->get('gift_card_amount_will_return')) > 0){
            $checkIfCanReturnMoneyByGiftCard = ReturnClientOrderItemsHelper::checkIfCanReturnMoneyByGiftCard($request, $orderData);
            if(!empty($checkIfCanReturnMoneyByGiftCard)){
                return $checkIfCanReturnMoneyByGiftCard;
            }
        }

        // check if amount to be returned is valid
        $theAmountToBeReturnedIsValid      = ReturnClientOrderItemsHelper::returnTheAmountToBeReturned($request, $orderItemsData);
        $checkTheAmountToBeReturnedIsValid = ReturnClientOrderItemsHelper::checkTheAmountToBeReturnedIsValid($request, $theAmountToBeReturnedIsValid);
        if (!empty($checkTheAmountToBeReturnedIsValid)) {
            return $checkTheAmountToBeReturnedIsValid;
        }

        $rules_values["item_ids"]        = $request->get("item_ids");
        $rules_values["want_return_qty"] = $request->get("want_return_qty");

        $rules_itself["item_ids.*"]        = "required|numeric";
        $rules_itself["want_return_qty.*"] = "required|numeric|min:1";


        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);
    }

    public function returnOrderItems(Request $request, $orderId)
    {
        if ($this->checkIsSetRegisterSessionIdInSession() === false){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'The session should start in your register'
            );
        }

        if ($request->method() == 'POST'){
            $orderItemsData     = collect(clients_order_items_m::getOrderItemsByOrderId($orderId));
            $orderData          = clients_orders_m::getAllClientOrders($orderId);
            $mainInvOfBranchObj = branch_inventory_m::getMainInvOfBranch($orderData->branch_id);
            $clientData         = clients_m::getClientDataById($orderData->client_id);

            if($orderData->can_not_return_items){
                return [
                    "error" => "you can not return items"
                ];
            }

            $validation = $this->returnOrderItemsValidation($request, $orderData, $orderItemsData);
            if ($validation !== true){
                return $validation;
            }

            \DB::beginTransaction();

            $amountWillReturn = ReturnClientOrderItemsHelper::returnItemsAndSaveRows($request, $orderData, $orderItemsData, $mainInvOfBranchObj);

            // decrease client order count and total amount
            if (ReturnClientOrderItemsHelper::checkIfAllOrderItemsWillReturn($orderData) === true){
                ReturnClientOrderItemsHelper::decreaseClientOrdersCountAndTotalAmountWhenReturnAllOrder($orderData, $amountWillReturn);
            }
            else {
                ReturnClientOrderItemsHelper::decreaseClientOrdersAmountWhenReturnPartOfOrder($orderData, $amountWillReturn);
            }


            ReturnClientOrderItemsHelper::updatePaymentMethodsAmountsWillReturn($request, $orderData, $clientData, $amountWillReturn);

            $orderDataAfterUpdate = clients_orders_m::getAllClientOrders($orderId);

            // create register session log
            $this->createRegisterSession(
                $request->session()->get('register_session_id', 1),
                $orderId,
                'order',
                'decrease',
                $orderDataAfterUpdate->cash_return_amount,
                $orderDataAfterUpdate->debit_card_return_amount,
                $orderDataAfterUpdate->credit_card_return_amount,
                $orderDataAfterUpdate->cheque_return_amount
            );

            // minus money from branch wallets
            ReturnClientOrderItemsHelper::decreaseWalletsOfBranchAfterReturnOrderItems($orderData->branch_id, $orderData, $clientData->client_name);

            // return money to wallets
            ReturnClientOrderItemsHelper::returnMoneyToWallet($request, $orderData, $clientData);
            ReturnClientOrderItemsHelper::returnMoneyToGiftCard($request, $orderData, $clientData);

            \DB::commit();

            return $this->returnMsgWithRedirection(
                $request,
                "admin/clients-orders/show-order/$orderId",
                "Items returned successfully",
                true
            );
        }

        return $this->showOrderDataWithItemsBasedOnView($request, $orderId, 'return_order');
    }

    #endregion


    public function cancelOrder(Request $request, $orderId)
    {

        //TODO add validation here

        clients_orders_m::changeOrderStatus($orderId, "cancel");

        return $this->returnMsgWithRedirection(
            $request,
            "admin/clients-orders/show-order/$orderId",
            "Order cancelled successfully"
        );
    }

}
