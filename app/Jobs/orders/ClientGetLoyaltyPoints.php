<?php

namespace App\Jobs\orders;

use App\btm_form_helpers\ErrorHelper;
use App\btm_form_helpers\WalletHelper;
use App\models\client\clients_m;
use App\models\client\clients_orders_m;
use App\models\money_to_loyalty_points_m;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClientGetLoyaltyPoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {

        /*
        $attrs = [
            "orderId"               => $orderId,
        ];
        */

        $this->attrs = $attrs;

    }


    public function handle()
    {

        try {

            $orderObj  = clients_orders_m::getAllClientOrders($this->attrs["orderId"]);
            $clientObj = clients_m::getClientDataById($orderObj->client_id);

            if (
                !is_object($orderObj) ||
                !is_object($clientObj)
            ) {
                return;
            }


            $paidMoney = $orderObj->total_paid_amount - $orderObj->total_return_amount;
            if ($paidMoney == 0) {
                return;
            }

            //add points to client
            $rawardRow = money_to_loyalty_points_m::getNearestRowAccordingToPaidMoney($paidMoney, $orderObj->branch_currency);
            if (!is_object($rawardRow)) {
                return;
            }

            \DB::beginTransaction();

            WalletHelper::depositMoney([
                "moneyAmount"         => $rawardRow->reward_points,
                "walletId"            => $clientObj->points_wallet_id,
                "walletOwnerName"     => $clientObj->client_name,
                "notes"               => "add these points because you've paid $paidMoney at order #" . $orderObj->client_order_id,
                "transactionCurrency" => "point",
            ]);


            //make order non-returnable
            clients_orders_m::updateOrderData([
                "can_not_return_items" => "1"
            ], $orderObj->client_order_id);


            \DB::commit();

        } catch (\Exception $exception) {
            ErrorHelper::handleException($exception);
        }


    }

}
