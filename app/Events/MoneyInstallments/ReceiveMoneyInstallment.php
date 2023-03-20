<?php

namespace App\Events\MoneyInstallments;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ReceiveMoneyInstallment
{
    use Dispatchable, InteractsWithSockets;

    public $installmentId;
    public $walletId;


    public function __construct(

        int $installmentId,
        int $walletId
    )
    {
        $this->installmentId = $installmentId;
        $this->walletId      = $walletId;

    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
