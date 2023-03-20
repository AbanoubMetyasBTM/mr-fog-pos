<?php

namespace App\Events\Wallets;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class WithdrawMoneyFromWallet
{
    use Dispatchable, InteractsWithSockets;

    public $adminId;
    public $walletId;
    public $walletOwnerName;
    public $transactionCurrency;
    public $moneyAmount;
    public $notes;
    public $checkIfAmountGreaterThanWalletAmount;
    public $sendEmail;
    public $transactionType;
    public $transactionMoneyType;


    public function __construct(
        int    $adminId,
        int    $walletId,
        string $walletOwnerName,
        string $transactionCurrency,
               $moneyAmount,
               $notes,
               $checkIfAmountGreaterThanWalletAmount = true,
               $sendEmail = false,
               $transactionType = null,
               $transactionMoneyType = null
    )
    {
        $this->adminId                              = $adminId;
        $this->walletId                             = $walletId;
        $this->walletOwnerName                      = $walletOwnerName;
        $this->transactionCurrency                  = $transactionCurrency;
        $this->moneyAmount                          = $moneyAmount;
        $this->notes                                = $notes;
        $this->checkIfAmountGreaterThanWalletAmount = $checkIfAmountGreaterThanWalletAmount;
        $this->sendEmail                            = $sendEmail;
        $this->transactionType                      = $transactionType;
        $this->transactionMoneyType                 = $transactionMoneyType;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
