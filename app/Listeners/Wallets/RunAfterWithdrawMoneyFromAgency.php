<?php

namespace App\Listeners\Wallets;

use App\btm_form_helpers\WalletHelper;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Http\Controllers\traits\DefaultEmailSettingsTrait;
use App\Http\Controllers\traits\notificationsTrait;
use App\models\transactions_log_m;
use App\models\wallets_m;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterWithdrawMoneyFromAgency implements ShouldQueue
{
    use InteractsWithQueue, DefaultEmailSettingsTrait, notificationsTrait;

    public function handle(WithdrawMoneyFromWallet $event)
    {

        $adminObj = User::getUserById($event->adminId);


        \DB::beginTransaction();

        $checkDone = WalletHelper::withdrawMoney([
            "moneyAmount"           => $event->moneyAmount,
            "walletId"              => $event->walletId,
            "walletOwnerName"       => $event->walletOwnerName,
            "notes"                 => $event->notes,
            "transactionType"       => $event->transactionType,
            "transactionCurrency"   => $event->transactionCurrency,
            "transactionMoneyType"  => $event->transactionMoneyType,
        ]);

        if ($checkDone !== true) {
            $this->generalSendEmail(
                $adminObj->email,
                "Withdraw Is Failed",
                $checkDone["error"]
            );
            return;
        }

        if ($event->sendEmail) {
            $this->generalSendEmail(
                $adminObj->email,
                "Admin-Withdraw Is Done Successfully",
                "Admin-Withdraw From  {$event->walletOwnerName} is done successfully"
            );
        }

        \DB::commit();

    }


}
