<?php

namespace App\Jobs\orders;

use App\btm_form_helpers\AddOrderHelper;
use App\btm_form_helpers\CommonBetweenOrderAndGiftCard;
use App\models\branch\branch_prices_m;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\client\clients_order_items_m;
use App\models\client\clients_orders_m;
use App\models\coupon\coupons_m;
use App\models\coupon\used_coupons_m;
use App\models\product\product_promotions_m;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakeOrderDoneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {

        /*
        $attrs = [
            "orderId"               => $orderId,
            "request"               => $request,
            "current_branch_id"     => $request->session()->get('current_branch_id'),
            "register_id"           => $request->session()->get('register_id'),
            "register_session_id"   => $request->session()->get('register_session_id'),
            "employee_id"           => $this->user_id,
        ];

        */

        $this->attrs = $attrs;

    }

    private function updateOrderDataWhenOrderDone(Request $request, $orderId)
    {

        $dataWillUpdate['order_status']              = 'done';

        $fields = [
            "wallet_paid_amount", "cash_paid_amount",
            "debit_card_paid_amount", "credit_card_paid_amount",
            "cheque_paid_amount", "total_paid_amount",
            "gift_card_id", "gift_card_paid_amount",
            "used_coupon_id", "used_coupon_value",
            "used_points_redeem_points", "used_points_redeem_money",
            "total_cost",
        ];

        foreach ($fields as $field) {
            if ($request->get($field) !== null) {
                $dataWillUpdate[$field] = $request->get($field);
            }
        }

        // update order data
        clients_orders_m::updateOrderData($dataWillUpdate, $orderId);
    }

    private function updateOrderItemsPrices($originalOrderItemsPrices, $orderItems)
    {

        foreach ($originalOrderItemsPrices as $key => $originalItem) {

            if ($originalItem["item_cost"] == $orderItems[$key]["item_cost"]) {
                continue;
            }

            clients_order_items_m::updateItemPrice(
                $originalItem["id"],
                $orderItems[$key]["item_cost"],
                $orderItems[$key]["order_quantity"]
            );

        }


    }

    public function handle()
    {

        //TODO wrap with try catch

        /**
         * @var $request Request
         */

        $request  = Request::capture()->merge($this->attrs["request"]);
        $branchId = $this->attrs["current_branch_id"];
        $orderId  = $this->attrs["orderId"];

        $branchData = branches_m::getBranchById($branchId);


        \DB::beginTransaction();

        $orderData  = clients_orders_m::getAllClientOrders($orderId);
        $orderItems = collect(clients_order_items_m::getOrderItemsByOrderId($orderId));


        // check coupon and calculate total cost
        $originalOrderItemsPrices = $orderItems->toArray();
        $orderItems               = ["data" => $orderItems->toArray()];

        $applyCouponData = AddOrderHelper::applyCoupon($request, $this->attrs["current_branch_id"], $orderItems);
        $orderItems      = $applyCouponData["orderItemsData"] ?? $orderItems;
        $request         = $request->merge($applyCouponData["mergeData"]);

        $applyRedeemData = AddOrderHelper::applyRedeem($request, $this->attrs["current_branch_id"], $orderItems);
        $orderItems      = $applyRedeemData["orderItemsData"] ?? $orderItems;
        $request         = $request->merge($applyRedeemData["mergeData"]);

        $orderItems      = $orderItems["data"];

        $this->updateOrderItemsPrices($originalOrderItemsPrices, $orderItems);

        $request = AddOrderHelper::calculateTotalPaidAmountBasedOnPriorityOfPaymentMethods($request, $request["total_cost"]);

        // upload receipts of payment methods images
        $request = CommonBetweenOrderAndGiftCard::uploadReceiptsImages($request);

        // update order data
        $this->updateOrderDataWhenOrderDone($request, $orderId);

        $orderData = clients_orders_m::getAllClientOrders($orderId);

        // use coupon
        if ($orderData->used_coupon_id != null){
            used_coupons_m::createUsedCoupon(
                $orderData->used_coupon_id,
                $orderData->branch_id,
                $orderData->client_id,
                $orderData->client_order_id,
                $orderData->used_coupon_value
            );
            coupons_m::increaseCouponUsedTimes($orderData->used_coupon_id);
        }

        // after make order done
        AddOrderHelper::afterAddDoneClientOrder(
            $request,
            $orderData,
            $orderItems,
            $this->attrs["employee_id"],
            $this->attrs["register_session_id"],
            $branchData->return_policy_days
        );


        \DB::commit();

    }

}
