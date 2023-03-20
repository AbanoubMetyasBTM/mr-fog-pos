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

class AddOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {

        /*
        $attrs = [
            "request"               => $request,
            "current_branch_id"     => $request->session()->get('current_branch_id'),
            "register_id"           => $request->session()->get('register_id'),
            "register_session_id"   => $request->session()->get('register_session_id'),
            "employee_id"           => $this->user_id,

        ];

        */

        $this->attrs = $attrs;

    }


    private function orderItemsHandler(Request $request, $taxes, $clientType):array
    {
        $items                     = [];
        $items["order_total_cost"] = 0;
        $branchId                  = $this->attrs["current_branch_id"];



        $taxesTotalValue           = collect($taxes)->pluck("tax_percent")->all();
        $taxesTotalValue           = array_sum($taxesTotalValue);

        $productSkusBranchPrice    = branch_prices_m::getPricesByBranchId($branchId);
        $clientType                = $clientType;
        $branchPromotions          = collect(product_promotions_m::getPromotionForSpecificBranchAndAllBranches($branchId));
        $promotionsIds             = $request->get("promo_id");

        foreach ($request->get("product_sku") as $key => $productSkuId) {
            // get price based on client type and item type

            $items['data'][$key]["pro_id"]           = $productSkusBranchPrice->where("sku_id", "=", $productSkuId)->pluck("pro_id")->first();
            $price                                   = $request->get("item_type")[$key] . "_" . $clientType . "_price";
            $items['data'][$key]["pro_sku_id"]       = $productSkuId;
            $items['data'][$key]["item_type"]        = $request->get("item_type")[$key];
            $items['data'][$key]["operation_type"]   = "buy";
            $items["data"][$key]["created_at"]       = now();
            $items['data'][$key]["order_quantity"]   = intval($request->get("order_quantity")[$key]);
            $items['data'][$key]["item_cost"]        = floatval($productSkusBranchPrice->where("sku_id", "=", $productSkuId)->pluck($price)->first());
            $items['data'][$key]["promotion_id"]     = null;

            //check if product has promotion
            if (isset($promotionsIds[$productSkuId])){
                $promotionObj = $branchPromotions->where('promo_id', '=', $promotionsIds[$productSkuId])->first();
                if (is_object($promotionObj)){
                    $items['data'][$key]["item_cost"]    = $items['data'][$key]["item_cost"] - ($items['data'][$key]["item_cost"] * (floatval($promotionObj->promo_discount_percent) / 100));
                    $items['data'][$key]["promotion_id"] = $promotionsIds[$productSkuId];
                }
            }

            //apply taxes
            $taxValue                         = floatval($items['data'][$key]["item_cost"]) * ($taxesTotalValue / 100);
            $taxValue                         = round($taxValue, 2);
            $items['data'][$key]["item_cost"] += $taxValue;

            $items['data'][$key]["total_items_cost"] = floatval($items['data'][$key]["item_cost"]) * $items['data'][$key]["order_quantity"];
            $items['order_total_cost']               =  $items['order_total_cost'] + $items['data'][$key]["total_items_cost"];
        }

        return $items;
    }


    public function handle()
    {

        //TODO wrap with try catch

        /**
         * @var $request Request
         */

        $request  = Request::capture()->merge($this->attrs["request"]);
        $branchId = $this->attrs["current_branch_id"];

        $branchObj                 = branches_m::getBranchById($branchId);
        $applyTaxes                = json_decode($branchObj->branch_taxes);

        $clientId                  = $request->get("client_id");
        $clientObj                 = clients_m::getClientDataById($clientId);
        if(!empty($clientObj->group_taxes)){
            $applyTaxes = json_decode($clientObj->group_taxes);
        }

        \DB::beginTransaction();

        // handle order items
        $orderItemsData = $this->orderItemsHandler($request, $applyTaxes, $clientObj->client_type);
        $request->merge([
            'total_items_cost' => $orderItemsData['order_total_cost']
        ]);

        $orderData['total_cost']          = $orderItemsData['order_total_cost'];
        $orderData['branch_id']           = $branchId;
        $orderData['register_id']         = $this->attrs["register_id"];
        $orderData['register_session_id'] = $this->attrs["register_session_id"];

        // @TODO Mas3ood => change in case online order
        $orderData['employee_id'] = $this->attrs["employee_id"];

        // @TODO Mas3ood => change in case online order
        $orderData['order_type'] = 'offline';

        // check qty of product inventory after buy
        $productsNotAvailableInMainInv = AddOrderHelper::checkIfQtyItemsAvailableInMainInventoryOfBranch($orderItemsData['data'], $branchId);
        if ($productsNotAvailableInMainInv !== true){
            //TODO send notification to the current user
            var_dump($productsNotAvailableInMainInv);
            return $productsNotAvailableInMainInv;
        }

        // check coupon and calculate total cost
        $applyCouponData = AddOrderHelper::applyCoupon($request, $this->attrs["current_branch_id"], $orderItemsData);
        $orderItemsData  = $applyCouponData["orderItemsData"];
        $orderData       = array_merge($orderData, $applyCouponData["mergeData"]);

        $applyRedeemData = AddOrderHelper::applyRedeem($request, $this->attrs["current_branch_id"], $orderItemsData);
        $orderItemsData  = $applyRedeemData["orderItemsData"];
        $orderData       = array_merge($orderData, $applyRedeemData["mergeData"]);


        // check amount paid before create order
        if ($request->get('order_status') == 'done'){
            $checkAmountPaid = AddOrderHelper::checkAmountPaidBasedOnClientType($request, $orderData['total_cost']);

            if ($checkAmountPaid !== true) {
                //TODO send notification to the current user
                var_dump($checkAmountPaid);
                return $checkAmountPaid;
            }

            $request = AddOrderHelper::calculateTotalPaidAmountBasedOnPriorityOfPaymentMethods($request, $orderData['total_cost']);

            // upload receipts of payment methods images
            $request = CommonBetweenOrderAndGiftCard::uploadReceiptsImages($request);
        }

        $orderData  = array_merge($orderData, $request->all());
        $branchData = branches_m::getBranchById($branchId);

        $orderData['order_timezone'] = $branchData->branch_timezone;
        $orderData['total_taxes']    = json_encode($applyTaxes);

        // create order data
        $orderObj = clients_orders_m::create($orderData);
        $orderObj = clients_orders_m::getAllClientOrders($orderObj->client_order_id);

        // create order items data
        clients_order_items_m::createOrderItems($orderItemsData['data'], $orderObj->client_order_id);

        // use coupon
        if ($orderObj->used_coupon_id != null){
            used_coupons_m::createUsedCoupon(
                $orderObj->used_coupon_id,
                $orderObj->branch_id,
                $orderObj->client_id,
                $orderObj->client_order_id,
                $orderObj->used_coupon_value
            );
            coupons_m::increaseCouponUsedTimes($orderObj->used_coupon_id);
        }


        if ($request->get("order_status") == "done") {
            AddOrderHelper::afterAddDoneClientOrder(
                $request,
                $orderObj,
                $orderItemsData['data'],
                $this->attrs["employee_id"],
                $this->attrs["register_session_id"],
                $branchData->return_policy_days
            );
        }

        \DB::commit();

    }

}
