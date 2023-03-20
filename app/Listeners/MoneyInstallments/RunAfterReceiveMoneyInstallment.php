<?php

namespace App\Listeners\MoneyInstallments;

use App\Events\MoneyInstallments\ReceiveMoneyInstallment;
use App\models\money_installments_m;
use App\models\transactions_log_m;
use App\models\wallets_m;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterReceiveMoneyInstallment implements ShouldQueue
{
    use InteractsWithQueue;
    public function handle(ReceiveMoneyInstallment $event)
    {


        $walletObj = wallets_m::getWalletById($event->walletId);

        $instalmentObj = money_installments_m::findOrFail($event->installmentId);



        if ($instalmentObj->money_type == 'debt'){

            $walletNewAmount      = $walletObj->wallet_amount - $instalmentObj->money_amount;
            $transactionOperation = 'decrease';
            $transactionType      = 'get_money_from_wallet';
            $transactionMsg       = "Decrease ( $instalmentObj->money_amount ) from wallet ( $event->walletId ) because it was paid in installments, installment id ($event->installmentId)";
        }
        else{

            $walletNewAmount      = $walletObj->wallet_amount + $instalmentObj->money_amount;
            $transactionOperation = 'increase';
            $transactionType      = 'add_money_to_wallet';
            $transactionMsg       = "Increase ( $instalmentObj->money_amount ) in wallet ( $event->walletId ) because it was paid in installments, installment id ($event->installmentId)";
        }


        money_installments_m::updateMoneyInstallmentIsReceive($event->installmentId);

        \DB::beginTransaction();

        // update wallet
        wallets_m::saveWallet($walletNewAmount, $event->walletId);


        // add transaction log
        transactions_log_m::create([
            'wallet_id'             => $event->walletId,
            'transaction_type'      => $transactionType,
            'transaction_operation' => $transactionOperation,
            'transaction_amount'    => $instalmentObj->money_amount,
            'transaction_notes'     => $transactionMsg
        ]);

        \DB::commit();
    }


}
