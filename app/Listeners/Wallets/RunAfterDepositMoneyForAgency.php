<?php

namespace App\Listeners\Wallets;

use App\btm_form_helpers\WalletHelper;
use App\Events\Wallets\DepositMoneyForWallet;
use App\Http\Controllers\traits\DefaultEmailSettingsTrait;
use App\Http\Controllers\traits\notificationsTrait;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterDepositMoneyForAgency implements ShouldQueue
{
    use InteractsWithQueue, DefaultEmailSettingsTrait, notificationsTrait;

    public function handle(DepositMoneyForWallet $event)
    {
        $adminObj    = User::getUserById($event->adminId);

        \DB::beginTransaction();

        $checkDone = WalletHelper::depositMoney([
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
                "Deposit Is Failed",
                $checkDone["error"]
            );
            return;
        }

        if ($event->sendMail === true){
            $this->generalSendEmail(
                $adminObj->email,
                "Admin-Deposit Is Done Successfully",
                "Admin-Deposit to Agency {$event->walletOwnerName} is done successfully"
            );
        }
        \DB::commit();
    }


}
