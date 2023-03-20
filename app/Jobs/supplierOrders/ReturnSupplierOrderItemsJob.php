<?php

namespace App\Jobs\supplierOrders;

use App\btm_form_helpers\AddSupplierOrderHelper;
use App\btm_form_helpers\WalletHelper;
use App\models\supplier\supplier_order_items_m;
use App\models\supplier\suppliers_m;
use App\models\supplier\suppliers_orders_m;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReturnSupplierOrderItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {
        /*
            $attrs = [
                'request_data'   => $request->all(),
                'order_id'       => 0,
                'return_amount'  => 0,
            ];
        */

        $this->attrs = $attrs;
    }


    public function handle()
    {

        /**
         * @var Request $request
         */

        $requestData = $this->attrs['requestData'];

        $request = Request::capture();
        $request = $request->merge($requestData);

        $orderId      = $this->attrs["order_id"];
        $allItemsData = collect(supplier_order_items_m::getOrderItemsByOrderId($orderId));
        $orderData    = suppliers_orders_m::getOrderById($orderId);
        $supplierData = suppliers_m::getSupplierDataById($orderData->supplier_id);
        $returnAmount = $this->attrs['returnAmount'];

        \DB::beginTransaction();

        AddSupplierOrderHelper::returnOrderItems($request, $allItemsData, $orderData, $supplierData);

        suppliers_orders_m::updateTotalReturnAmount($orderId, $request->get("returned_amount"));

        // minus paid money from supplier wallet
        $receivedAmount = $request->get("received_amount");


        if ($receivedAmount > 0) {
            $moneyAmount = round(floatval($returnAmount) - floatval($receivedAmount), 2);

            $withdrawNotes = "has been withdrawn ($moneyAmount) form supplier ($supplierData->sup_name)
            because return items of order ($orderId), return money ($returnAmount) and we receive ($receivedAmount)
            and the rest of the amount is ($moneyAmount)";
        }
        else {
            $moneyAmount   = $returnAmount;
            $withdrawNotes = "has been withdrawn ($returnAmount) form supplier ($supplierData->sup_name)
            because return items of order ($orderId)";
        }

        if ($moneyAmount > 0) {
            WalletHelper::withdrawMoney([
                "moneyAmount"                  => $moneyAmount,
                "walletId"                     => $supplierData->wallet_id,
                "walletOwnerName"              => $supplierData->sup_name,
                "notes"                        => $withdrawNotes,
                "transactionCurrency"          => $supplierData->sup_currency,
                "passEnoughMoneyAtWalletCheck" => true,
            ]);
        }

        \DB::commit();
    }

}
