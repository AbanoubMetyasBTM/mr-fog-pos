<?php

namespace App\Events\Wallets;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class DepositMoneyForWallet
{
    use Dispatchable, InteractsWithSockets;

    public $adminId;
    public $walletId;
    public $walletOwnerName;
    public $transactionCurrency;
    public $moneyAmount;
    public $notes;
    public $sendMail;
    public $transactionType;
    public $transactionMoneyType;

    public function __construct(
        int    $adminId,
        int    $walletId,
        string $walletOwnerName,
        string $transactionCurrency,
               $moneyAmount,
               $notes,
               $sendMail = false,
               $transactionType = null,
               $transactionMoneyType = null
    )
    {
        $this->adminId              = $adminId;
        $this->walletId             = $walletId;
        $this->walletOwnerName      = $walletOwnerName;
        $this->transactionCurrency  = $transactionCurrency;
        $this->moneyAmount          = $moneyAmount;
        $this->notes                = $notes;
        $this->sendMail             = $sendMail;
        $this->transactionType      = $transactionType;
        $this->transactionMoneyType = $transactionMoneyType;

    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
