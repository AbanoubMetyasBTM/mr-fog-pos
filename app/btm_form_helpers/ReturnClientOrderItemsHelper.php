<?php


namespace App\btm_form_helpers;


use App\Events\Wallets\DepositMoneyForWallet;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\client\clients_order_items_m;
use App\models\client\clients_orders_m;
use App\models\gift_card\gift_cards_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class ReturnClientOrderItemsHelper
{


    public static function checkIfItemsQtyWillReturnIsCorrect(Request $request, $orderItems)
    {
        $itemsIdsWillReturn = $request->get('item_ids');
        $itemsWillReturnQty = $request->get('want_return_qty');

        $msgs = '';
        foreach ($itemsIdsWillReturn as $key => $itemId){

            $item = collect($orderItems)->where('operation_type','=','buy')->where('id','=', $itemId)->first();

            $item->returned_qty =
                collect($orderItems)->where("pro_sku_id", "=", $item->pro_sku_id)->
                where("item_type", "=", $item->item_type)->
                where("operation_type", "=", "return")->
                pluck("order_quantity")->
                sum();

            if($itemsWillReturnQty[$key] < 0 && empty($itemsWillReturnQty[$key])){
                $itemsWillReturnQty[$key] = 0;
            }


            $availableQtyToReturn = intval($item->order_quantity) - intval($item->returned_qty);

            if ($itemsWillReturnQty[$key] >$availableQtyToReturn){
                $msgs .= "The quantity that will be returned from the product $item->product_name is greater than the quantity available for return <br>";
            }


        }

        return $msgs;
    }

    public static function checkTheAmountToBeReturnedIsValid(Request $request, $availableAmountToReturn)
    {
        $totalAmountWillReturn = floatval($request->get('wallet_amount_will_return')) +
            floatval($request->get('cash_amount_will_return')) +
            floatval($request->get('debit_card_amount_will_return')) +
            floatval($request->get('credit_card_amount_will_return')) +
            floatval($request->get('cheque_amount_will_return')) +
            floatval($request->get('gift_card_amount_will_return'));


        if ($totalAmountWillReturn > $availableAmountToReturn){
            return [
                "error" => "The amount to be returned {$totalAmountWillReturn} is greater than the amount available for return {$availableAmountToReturn}",
            ];
        }

        if ($totalAmountWillReturn < $availableAmountToReturn){
            return [
                "error" => "Please return the remaining amount ({$availableAmountToReturn}) to the client",
            ];
        }


    }

    public static function checkIfClientCanReturnMoney(Request $request, $clientId)
    {
        $clientObj         = clients_m::getClientDataById($clientId);
        $walletObjOfClient = wallets_m::getWalletById($clientObj->wallet_id);

        if ($walletObjOfClient->wallet_amount < 0 && floatval($request->get('wallet_amount_will_return')) > 0){
            $walletObjOfClient->wallet_amount += floatval($request->get('wallet_amount_will_return'));
        }

        if (
            $walletObjOfClient->wallet_amount < 0 &&
            (
                $request->get('cash_amount_will_return') > 0 ||
                $request->get('debit_card_amount_will_return') > 0 ||
                $request->get('credit_card_amount_will_return') > 0 ||
                $request->get('cheque_amount_will_return') > 0 ||
                $request->get('gift_card_amount_will_return') > 0
            )

        ){

            $walletObjOfClient->wallet_amount = abs($walletObjOfClient->wallet_amount);

            return [
                "error" => "
                    The client's wallet has debts of $walletObjOfClient->wallet_amount,
                    so the amount cannot be returned as anything but wallet,
                    use the wallet please
                ",
            ];

        }

    }

    public static function checkIfCanReturnMoneyByGiftCard(Request $request, $orderData)
    {
        if ($orderData->gift_card_paid_amount == null || $orderData->gift_card_paid_amount == 0 ){
            return [
                'error' => 'This order does not contain gift card, the amount cannot be returned by this payment method',
            ];
        }

        $availableAmountCanReturnByGiftCard = floatval($orderData->gift_card_paid_amount) - floatval($orderData->gift_card_return_amount);

        if (floatval($request->gift_card_amount_will_return) > $availableAmountCanReturnByGiftCard){
            return [
                'error' => 'The amount required to be returned by gift card is greater than the value of the gift card inside order',
            ];
        }

    }

    public static function returnTheAmountToBeReturned(Request $request, $orderItemsData)
    {

        $amountWillReturn = 0;

        foreach ($request->get("item_ids") as $key => $itemId) {

            if (empty($request->get("want_return_qty")[$key])) {
                continue;
            }

            $itemData = $orderItemsData->where("id", "=", $itemId)->first();

            if (!is_object($itemData)) {
                continue;
            }

            $totalReturnAmount = floatval($request->get("want_return_qty")[$key]) * floatval($itemData->item_cost);
            $amountWillReturn  += $totalReturnAmount;
        }

        return $amountWillReturn;

    }

    public static function returnItemsAndSaveRows(Request $request, $orderData, $orderItemsData, $mainInvOfBranchObj)
    {

        $data             = [];
        $amountWillReturn = 0;

        foreach ($request->get("item_ids") as $key => $itemId) {

            if (empty($request->get("want_return_qty")[$key])) {
                continue;
            }

            $itemData = $orderItemsData->where("id", "=", $itemId)->first();

            if (!is_object($itemData)) {
                continue;
            }

            $data[$key]["operation_type"]   = "return";
            $data[$key]["client_order_id"]  = $itemData->client_order_id;
            $data[$key]["pro_id"]           = $itemData->pro_id;
            $data[$key]["pro_sku_id"]       = $itemData->pro_sku_id;
            $data[$key]["item_type"]        = $itemData->item_type;
            $data[$key]["item_cost"]        = $itemData->item_cost;
            $data[$key]["order_quantity"]   = $request->get("want_return_qty")[$key];
            $data[$key]["created_at"]       = now();
            $totalReturnAmount              = floatval($data[$key]["order_quantity"]) * floatval($data[$key]["item_cost"]);
            $data[$key]["total_items_cost"] = round($totalReturnAmount,2);

            if ($data[$key]["item_type"] == "box") {
                $ipBoxQty  = $data[$key]["order_quantity"];
                $ipItemQty = 0;
            }
            else {
                $ipBoxQty  = 0;
                $ipItemQty = $data[$key]["order_quantity"];
            }

            //increase items to main inv of branch
            InventoryHelper::addProductToInventory([
                "inventoryId"    => $mainInvOfBranchObj->inventory_id,
                "productId"      => $data[$key]["pro_id"],
                "proSkuId"       => $data[$key]["pro_sku_id"],
                "ipBoxQuantity"  => $ipBoxQty,
                "ipItemQuantity" => $ipItemQty,
                "limitItemsQty"  => 0,
                "logDesc"        => "return order items of order ($orderData->client_order_id)",
            ]);


            $amountWillReturn += $data[$key]["total_items_cost"];
        }


        // update payment methods amount will return
        clients_order_items_m::insert($data);

        return $amountWillReturn;

    }

    public static function updatePaymentMethodsAmountsWillReturn(Request $request, $orderData, $clientData, $amountWillReturn)
    {

        $data['wallet_return_amount']      = floatval($orderData->wallet_return_amount) + floatval($request->get('wallet_amount_will_return'));
        $data['gift_card_return_amount']   = floatval($orderData->gift_card_return_amount) + floatval($request->get('gift_card_amount_will_return'));
        $data['cash_return_amount']        = floatval($orderData->cash_return_amount) + floatval($request->get('cash_amount_will_return'));
        $data['debit_card_return_amount']  = floatval($orderData->debit_card_return_amount) + floatval($request->get('debit_card_amount_will_return'));
        $data['credit_card_return_amount'] = floatval($orderData->credit_card_return_amount) + floatval($request->get('credit_card_amount_will_return'));
        $data['cheque_return_amount']      = floatval($orderData->cheque_return_amount) + floatval($request->get('cheque_amount_will_return'));
        $data['total_return_amount']       = floatval($orderData->total_return_amount) + floatval($amountWillReturn);

        clients_orders_m::updateOrderData($data, $orderData->client_order_id);

    }

    public static function checkIfAllOrderItemsWillReturn($orderData):bool
    {
        $items = clients_order_items_m::getOrderItemsByOrderId($orderData->client_order_id);

        foreach ($items as $item) {
            if ($item->operation_type == "buy") {
                $item->returned_qty = collect($items)->
                    where("pro_sku_id", "=", $item->pro_sku_id)->
                    where("item_type", "=", $item->item_type)->
                    where("operation_type", "=", "return")->
                    pluck("order_quantity")->sum();

                if (intval($item->returned_qty) != intval($item->order_quantity)){
                    return false;
                }
            }
        }

        return true;

    }


    public static function decreaseWalletsOfBranchAfterReturnOrderItems($branchId, $orderData, $clientName)
    {
        $branchObj = branches_m::getBranchById($branchId);

        $mapArr = [
            "cash_paid_amount"        => "cash_wallet_id",
            "debit_card_paid_amount"  => "debit_card_wallet_id",
            "credit_card_paid_amount" => "credit_card_wallet_id",
            "cheque_paid_amount"      => "cheque_wallet_id",
        ];

        foreach ($mapArr as $paidType=>$paidWalletType){
            if($orderData->{$paidType} == 0){
                continue;
            }

            self::decreaseWalletsOfBranchBasedOnWalletType(
                $branchObj,
                $orderData->client_order_id,
                $branchObj->{$paidWalletType},
                $orderData->{$paidType},
                $clientName
            );
        }

    }

    public static function decreaseWalletsOfBranchBasedOnWalletType($branchObj, $orderId, $walletId, $amount, $clientName)
    {

        $branchName = $branchObj->branch_name;

        $logNotes = "An amount of ($amount) was Withdrawn from the wallet of the branch ($branchName) due to return items
        of order ( $orderId ) to the client that belongs to the client ($clientName)";

        WalletHelper::withdrawMoney([
            "moneyAmount"         => $amount,
            "walletId"            => $walletId,
            "walletOwnerName"     => $branchName,
            "notes"               => $logNotes,
            "transactionCurrency" => $branchObj->branch_currency,
        ]);

    }

    public static function decreaseClientOrdersCountAndTotalAmountWhenReturnAllOrder($orderData, $amountWillReturn)
    {
        $clientData                         = clients_m::getClientDataById($orderData->client_id);
        $data['client_total_orders_count']  = intval($clientData->client_total_orders_count) - 1;
        $data['client_total_orders_amount'] = floatval($clientData->client_total_orders_amount) - floatval($amountWillReturn);
        clients_m::updateClientData($orderData->client_id, $data);

    }

    public static function decreaseClientOrdersAmountWhenReturnPartOfOrder($orderData, $amountWillReturn)
    {
        $clientData                         = clients_m::getClientDataById($orderData->client_id);
        $data['client_total_orders_amount'] = floatval($clientData->client_total_orders_amount) - floatval($amountWillReturn);
        clients_m::updateClientData($orderData->client_id, $data);

    }


    public static function returnMoneyToGiftCard(Request $request, $orderData, $clientData)
    {

        if (floatval($request->get('gift_card_amount_will_return')) == 0) {
            return;
        }

        $giftCardObj              = gift_cards_m::getGiftCardById($orderData->gift_card_id);
        $giftCardAmountWillReturn = floatval($request->get('gift_card_amount_will_return'));

        $logNotes = "An amount of ($giftCardAmountWillReturn) was added due to return items of order
                ($orderData->client_order_id) that belongs to the client ($clientData->client_name) to gift card
                ($giftCardObj->card_id)";

        WalletHelper::depositMoney([
            "moneyAmount"           => $giftCardAmountWillReturn,
            "walletId"              => $giftCardObj->wallet_id,
            "walletOwnerName"       => $clientData->client_name,
            "notes"                 => $logNotes,
            "transactionCurrency"   => $giftCardObj->branch_currency,
        ]);

    }

    public static function returnMoneyToWallet(Request $request, $orderData, $clientData)
    {

        if (floatval($request->get('wallet_amount_will_return')) == 0) {
            return;
        }

        $walletAmountWillReturn = floatval($request->get('wallet_amount_will_return'));

        $logNotes = "An amount of ($walletAmountWillReturn) was added due to return items of order
                ($orderData->client_order_id) that belongs to the client ($clientData->client_name)";

        WalletHelper::depositMoney([
            "moneyAmount"         => $walletAmountWillReturn,
            "walletId"            => $clientData->wallet_id,
            "walletOwnerName"     => $clientData->client_name,
            "notes"               => $logNotes,
            "transactionCurrency" => $orderData->branch_currency,
        ]);


    }


}
