<?php

namespace App\btm_form_helpers;

use App\Jobs\orders\ClientGetLoyaltyPoints;
use App\models\branch\branch_inventory_m;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\coupon\coupons_m;
use App\models\coupon\used_coupons_m;
use App\models\gift_card\gift_cards_m;
use App\models\inventory\inventories_products_m;
use App\models\loyalty_points_to_money_m;
use App\models\product\product_skus_m;
use App\models\wallets_m;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AddOrderHelper
{

    public static function checkIfQtyItemsAvailableInMainInventoryOfBranch($items, $branchId)
    {
        $itemsSkuIds       = collect($items)->pluck("pro_sku_id")->toArray();
        $mainInvOfBranch   = branch_inventory_m::getMainInvOfBranch($branchId)->inventory_id;
        $productsInMainInv = collect(inventories_products_m::getInventoryProductByInvIdAndProductsSkuIds($mainInvOfBranch, $itemsSkuIds));
        $productsObjs      = collect(product_skus_m::getProductsSkusWithVariantValuesByIds($itemsSkuIds));

        $remainItemsAndBoxesQtyAfterBuy = [];
        $productsWillNotAvailableInInv  = [];
        foreach ($items as $key => $item) {

            $productInMainInvQty = $productsInMainInv->where('pro_sku_id', '=', $item['pro_sku_id'])->first();
            $proStandardBoxQty   = $productsObjs->where('pro_id', '=', $item['pro_id'])->
            pluck('standard_box_quantity')->first();
            $availableBoxQty     = $productInMainInvQty->ip_box_quantity;
            $availableItemQty    = $productInMainInvQty->ip_item_quantity;

            if ($item['item_type'] == 'item') {

                $sameItemTypeBoxQty = collect($items)->
                where('pro_sku_id', '=', $item['pro_sku_id'])->
                where('item_type', '=', 'box')->first();

                $wantedItemQty = $item['order_quantity'];
                $wantedBoxQty  = !empty($sameItemTypeBoxQty) ? $sameItemTypeBoxQty['order_quantity'] : 0;
            } else {
                $sameItemTypeItemQty = collect($items)->
                where('pro_sku_id', '=', $item['pro_sku_id'])->
                where('item_type', '=', 'item')->first();

                $wantedItemQty = !empty($sameItemTypeItemQty) ? $sameItemTypeItemQty['order_quantity'] : 0;
                $wantedBoxQty  = $item['order_quantity'];
            }

            $remainItemsAndBoxesQtyAfterBuy[$key] = calculateProductBoxesAndItemsAfterUsed(
                $proStandardBoxQty,
                $availableBoxQty,
                $availableItemQty,
                $wantedBoxQty,
                $wantedItemQty
            );

            if ($remainItemsAndBoxesQtyAfterBuy[$key]['new_boxes_qty'] < 0 || $remainItemsAndBoxesQtyAfterBuy[$key]['new_items_qty'] < 0) {

                $availableProductQty = "ip_" . $item['item_type'] . "_quantity";
                $productsWillNotAvailableInInv[]
                                     = $productsObjs->where('ps_id', '=', $item['pro_sku_id'])->
                    pluck('product_name')->first() .
                    " ( " . $item['item_type'] . " ) available qty " .
                    $productInMainInvQty->{$availableProductQty};
            }

        }

        if (count($productsWillNotAvailableInInv)) {
            return [
                "error" => implode('<br>', $productsWillNotAvailableInInv)
            ];
        }

        return true;
    }

    #region coupon & redeem
    public static function checkIfCouponIsUsable(Request $request, $currentBranchId)
    {
        $clientId   = $request->get("client_id");
        $couponCode = $request->get("coupon_code");
        $branchId   = $currentBranchId;
        $coupon     = coupons_m::getCouponByCodeAndBranchId($couponCode, $branchId);

        if (empty($couponCode)) {
            return;
        }

        if (is_null($coupon)) {
            return [
                "error" => "This coupon is invalid or has expired"
            ];
        }

        $usedCouponObj = used_coupons_m::checkIfCouponUsedBySpecificClient($clientId, $coupon->coupon_id);
        if (!is_null($usedCouponObj)) {
            return [
                "error" => "This coupon has already been used by this client"
            ];
        }

        $data                         = [];
        $data["data"]['coupon_type']  = $coupon->coupon_code_type;
        $data["data"]['coupon_value'] = $coupon->coupon_code_value;
        $data["data"]['coupon_id']    = $coupon->coupon_id;

        return $data;
    }

    public static function calculateTotalClientOrderItemsAfterUseCoupon($orderItems, $couponType, $couponValue)
    {
        $orderItems['order_total_cost'] = 0;

        if ($couponType == 'value') {
            $itemsCount                  = collect($orderItems['data'])->pluck('order_quantity')->sum();
            $valueWillDiscountedFromItem = round(($couponValue / $itemsCount), 2);
            $totalCostBeforeDiscount     = 0;

            foreach ($orderItems['data'] as $key => $item) {
                $totalCostBeforeDiscount                      = $totalCostBeforeDiscount + ($item['item_cost'] * $item['order_quantity']);
                $orderItems['data'][$key]['item_cost']        = $item['item_cost'] - $valueWillDiscountedFromItem;
                $orderItems['data'][$key]['total_items_cost'] = $orderItems['data'][$key]['item_cost'] * $item['order_quantity'];
            }

            $orderItems['order_total_cost']                   = $totalCostBeforeDiscount - $couponValue;

        } else {
            foreach ($orderItems['data'] as $key => $item) {
                $orderItems['data'][$key]['item_cost'] = $item['item_cost'] - (floatval($item['item_cost'])* (floatval($couponValue) / 100));

                $orderItems['data'][$key]['total_items_cost'] = $orderItems['data'][$key]['item_cost'] * $item['order_quantity'];
                $orderItems['order_total_cost']               = $orderItems['order_total_cost'] + $orderItems['data'][$key]["total_items_cost"];
            }
        }


        return $orderItems;
    }

    public static function applyCoupon($request, $currentBranchId, $orderItemsData): array
    {
        $coupon = AddOrderHelper::checkIfCouponIsUsable($request, $currentBranchId);
        if (!isset($coupon['data'])) {
            return [
                "orderItemsData" => $orderItemsData,
                "mergeData"      => [],
            ];
        }

        $orderItemsData = AddOrderHelper::calculateTotalClientOrderItemsAfterUseCoupon(
            $orderItemsData,
            $coupon['data']['coupon_type'],
            $coupon['data']['coupon_value']
        );

        return [
            "orderItemsData" => $orderItemsData,
            "mergeData"      => [
                "used_coupon_id"    => $coupon['data']['coupon_id'],
                "used_coupon_value" => $coupon['data']['coupon_value'],
                "total_cost"        => $orderItemsData['order_total_cost']
            ],
        ];
    }

    public static function applyRedeem($request, $currentBranchId, $orderItemsData): array
    {
        $pointsRedeem = AddOrderHelper::getDiscountMoneyFromLoyaltyRedeem($request, $currentBranchId);
        if ($pointsRedeem === false) {
            return [
                "orderItemsData" => $orderItemsData,
                "mergeData"      => [],
            ];
        }

        $orderItemsData = AddOrderHelper::calculateTotalClientOrderItemsAfterUseCoupon(
            $orderItemsData,
            "value",
            $pointsRedeem["reward_money"]
        );

        return [
            "orderItemsData" => $orderItemsData,
            "mergeData"      => [
                "used_points_redeem_points" => $pointsRedeem['points_amount'],
                "used_points_redeem_money"  => $pointsRedeem["reward_money"],
                "total_cost"                => $orderItemsData['order_total_cost'],
            ],
        ];
    }
    #endregion

    public static function getDiscountMoneyFromLoyaltyRedeem(Request $request, $currentBranchId)
    {

        $clientId          = $request->get("client_id");
        $clientObj         = clients_m::getClientDataById($clientId);
        $selectedRedeemId  = $request->get("selected_redeem");
        $selectedRedeemObj = loyalty_points_to_money_m::find($selectedRedeemId);
        $currentBranchData = branches_m::getBranchFromCache($currentBranchId);

        if(
            !is_object($clientObj) ||
            !is_object($selectedRedeemObj) ||
            !is_object($currentBranchData) ||
            $currentBranchData->branch_currency != $selectedRedeemObj->money_currency ||
            $clientObj->points_wallet_value < $selectedRedeemObj->points_amount
        ){
            return false;
        }

        return [
            "points_amount" => $selectedRedeemObj->points_amount,
            "reward_money"  => $selectedRedeemObj->reward_money
        ];

    }

    public static function checkAmountPaidBasedOnClientType(Request $request, $totalOrderCost)
    {

        $clientId             = $request->get('client_id');
        $clientWallet         = $request->get('wallet_paid_amount');
        $giftCard             = $request->get('gift_card');
        $cashPaidAmount       = $request->get('cash_paid_amount');
        $debitCardPaidAmount  = $request->get('debit_card_paid_amount');
        $creditCardPaidAmount = $request->get('credit_card_paid_amount');
        $chequePaidAmount     = $request->get('cheque_paid_amount');

        $clientData           = clients_m::getClientDataById($clientId);

        $totalAmountPaid =
            floatval($cashPaidAmount) +
            floatval($chequePaidAmount) +
            floatval($debitCardPaidAmount) +
            floatval($creditCardPaidAmount) +
            floatval($clientWallet);


        if ($giftCard != '') {
            $card           = gift_cards_m::getGiftCardByUniqueNumber($giftCard);
            $giftCardWallet = wallets_m::getWalletById($card->wallet_id);

            if (floatval($giftCardWallet->wallet_amount) > floatval($totalOrderCost)) {
                $totalAmountPaid += floatval($totalOrderCost);
            }
            else{
                $totalAmountPaid += floatval($giftCardWallet->wallet_amount);
            }
        }

        if (
            $clientData->client_type != 'wholesaler' &&
            $totalAmountPaid < floatval($totalOrderCost)
        ) {
            return [
                "error" => "The amount paid: $totalAmountPaid is less than the cost of the order: $totalOrderCost",
            ];
        }

        if($totalAmountPaid > $totalOrderCost){
            return [
                "error" => "You can not pay ($totalAmountPaid) more then order cost: $totalOrderCost",
            ];
        }

        return true;
    }

    public static function calculateTotalPaidAmountBasedOnPriorityOfPaymentMethods(Request $request, $orderCost): Request
    {
        $paidAmount = 0;

        if ($request->get('wallet_paid_amount') != '') {
            $paidAmount = $request->get('wallet_paid_amount');
        }

        if ($request->get('gift_card') != '') {
            $card = gift_cards_m::getGiftCardByUniqueNumber($request->get("gift_card"));

            $request["gift_card_id"] = $card->card_id;

            if (floatval($card->wallet_amount) >= floatval($orderCost)) {
                $cardValueWillPaid                = floatval($card->wallet_amount) - (floatval($card->wallet_amount) - floatval($orderCost));
                $request["gift_card_paid_amount"] = $cardValueWillPaid;
            }
            else {
                $request["gift_card_paid_amount"] = floatval($card->wallet_amount);
            }

            $paidAmount += $request->get('gift_card_paid_amount');
        }

        $paidAmount +=
            floatval($request->get('debit_card_paid_amount')) +
            floatval($request->get('cash_paid_amount')) +
            floatval($request->get('credit_card_paid_amount')) +
            floatval($request->get('cheque_paid_amount'));

        $request["total_paid_amount"] = $paidAmount;

        return $request;
    }


    #region afterAddDoneClientOrder

    private static function decreaseInventory($orderData, $orderItemsData, $client)
    {

        $mainInvOfBranch  = branch_inventory_m::getMainInvOfBranch($orderData->branch_id);


        foreach ($orderItemsData as $item) {

            if ($item["item_type"] == "box") {
                $ipBoxQty  = $item["order_quantity"];
                $ipItemQty = 0;
            }
            else {
                $ipBoxQty  = 0;
                $ipItemQty = $item["order_quantity"];
            }

            InventoryHelper::buyProductFromInventory([
                "inventoryId"    => $mainInvOfBranch->inventory_id,
                "productId"      => $item["pro_id"],
                "proSkuId"       => $item["pro_sku_id"],
                "ipBoxQuantity"  => (int)$ipBoxQty,
                "ipItemQuantity" => (int)$ipItemQty,
                "logDesc"        => "buy items of order ($orderData->client_order_id) for client ( $client->client_name )",
            ]);

        }

    }

    private static function decreaseClientWallet($orderData, $client)
    {

        if (
            empty($orderData->wallet_paid_amount) &&
            $client->clinet_type == "retailer"
        ){
            return;
        }

        $amountWillWithdrawFromWallet = floatval($orderData->wallet_paid_amount);
        $withdrawNotes                = "has been withdrawn money ($amountWillWithdrawFromWallet) from
                                            ($client->client_name) for order ($orderData->client_order_id)";

        WalletHelper::withdrawMoney([
            "moneyAmount"           => $amountWillWithdrawFromWallet,
            "walletId"              => $client->wallet_id,
            "walletOwnerName"       => $client->client_name,
            "notes"                 => $withdrawNotes,
            "transactionCurrency"   => $orderData->branch_currency,
        ]);

        // in case of wholesaler did not pay all order, so the system will deduct the difference
        // from his wallet
        if (floatval($orderData->total_cost) > floatval($orderData->total_paid_amount)){

            $amountWillWithdrawFromWallet = floatval($orderData->total_cost) - floatval($orderData->total_paid_amount);
            $withdrawNotes                = "has been withdrawn money ($amountWillWithdrawFromWallet) from
                                            ($client->client_name) for order ($orderData->client_order_id)";
            WalletHelper::withdrawMoney([
                "moneyAmount"                  => $amountWillWithdrawFromWallet,
                "walletId"                     => $client->wallet_id,
                "walletOwnerName"              => $client->client_name,
                "notes"                        => $withdrawNotes,
                "transactionCurrency"          => $orderData->branch_currency,
                "passEnoughMoneyAtWalletCheck" => true,
            ]);

        }

    }

    private static function decreaseGiftCardWallet($orderData, $client)
    {

        if (empty($orderData->gift_card_paid_amount)){
            return;
        }

        $walletObjOfGiftCard   = gift_cards_m::getGiftCardById($orderData->gift_card_id);
        $withdrawFromCardNotes = "has been withdrawn money ($orderData->gift_card_paid_amount) from gift card ($orderData->gift_card_id)
                                      by client ($client->client_name) for order ($orderData->client_order_id)";

        WalletHelper::withdrawMoney([
            "moneyAmount"           => $orderData->gift_card_paid_amount,
            "walletId"              => $walletObjOfGiftCard->wallet_id,
            "walletOwnerName"       => $client->client_name,
            "notes"                 => $withdrawFromCardNotes,
            "transactionCurrency"   => $orderData->branch_currency,
        ]);

    }

    private static function decreaseLoyaltyPoints($orderData, $client)
    {

        if (empty($orderData->used_points_redeem_points)){
            return;
        }

        $withdrawNotes = "has been withdrawn money ($orderData->used_points_redeem_points) from your points
                          for order ($orderData->client_order_id)";

        WalletHelper::withdrawMoney([
            "moneyAmount"           => $orderData->used_points_redeem_points,
            "walletId"              => $client->points_wallet_id,
            "walletOwnerName"       => $client->client_name,
            "notes"                 => $withdrawNotes,
            "transactionCurrency"   => "point",
        ]);

    }


    private static function increaseWalletsOfBranchAfterMakeClientOrder($branchId, $orderData, $clientName, $currentUserId)
    {
        $branchObj = branches_m::getBranchById($branchId);

        $arrMap = [
            "cash_paid_amount"        => "cash_wallet_id",
            "debit_card_paid_amount"  => "debit_card_wallet_id",
            "credit_card_paid_amount" => "credit_card_wallet_id",
            "cheque_paid_amount"      => "cheque_wallet_id"
        ];

        foreach ($arrMap as $paidType => $walletPaidType){

            if ($orderData->{$paidType} == 0 ){
                continue;
            }

            self::increaseWalletsOfBranchBasedOnWalletType(
                $branchObj,
                $orderData->client_order_id,
                $branchObj->{$walletPaidType},
                $orderData->{$paidType},
                $clientName,
                $currentUserId
            );

        }


    }

    private static function increaseWalletsOfBranchBasedOnWalletType($branchObj, $orderId, $walletId, $amount, $clientName, $currentUserId)
    {

        $branchName = $branchObj->branch_name;

        $logNotes = "
            An amount of ($amount) was added to the wallet of the branch ($branchName)
            due to selling the order ( $orderId ) to the client ($clientName)
        ";

        WalletHelper::depositMoney([
            "moneyAmount"           => $amount,
            "walletId"              => $walletId,
            "walletOwnerName"       => $branchName,
            "notes"                 => $logNotes,
            "transactionCurrency"   => $branchObj->branch_currency,
        ]);

    }

    private static function increaseClientOrdersCountAndTotalAmount($orderData)
    {
        $clientData                         = clients_m::getClientDataById($orderData->client_id);
        $data['client_total_orders_count']  = intval($clientData->client_total_orders_count) + 1;
        $data['client_total_orders_amount'] = floatval($clientData->client_total_orders_amount) + floatval($orderData->total_cost);

        clients_m::updateClientData($orderData->client_id, $data);
    }

    public static function afterAddDoneClientOrder(
        Request $request,
        $orderData,
        $orderItemsData,
        $currentUserId,
        $registerSessionId,
        $branchReturnPolicyDays
    )
    {

        $client           = clients_m::getClientDataById($orderData->client_id);


        self::decreaseInventory($orderData, $orderItemsData, $client);
        self::decreaseClientWallet($orderData, $client);
        self::decreaseGiftCardWallet($orderData, $client);
        self::decreaseLoyaltyPoints($orderData, $client);


        $job = (new ClientGetLoyaltyPoints([
            "orderId" => $orderData->client_order_id,
        ]))->delay(Carbon::now()->addDays($branchReturnPolicyDays));

        dispatch($job);

        // increase branch wallets
        self::increaseWalletsOfBranchAfterMakeClientOrder($orderData->branch_id, $orderData, $client->client_name, $currentUserId);


        // create register session log
        RegisterSessionLogHelper::createRegisterSession(
            $registerSessionId,
            $orderData->client_order_id,
            'order',
            'increase',
            $orderData->cash_paid_amount,
            $orderData->debit_card_paid_amount,
            $orderData->credit_card_paid_amount,
            $orderData->cheque_paid_amount
        );

        self::increaseClientOrdersCountAndTotalAmount($orderData);

    }

    #endregion

}
