<?php

namespace App\Events\InventoryLogs;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class AddInvalidEntryProduct
{
    use Dispatchable, InteractsWithSockets;
    public $inventoryLogId;
    public $inventoryId;
    public $productId;
    public $proSkuId;
    public $ipBoxQuantity;
    public $ipItemQuantity;
    public $logNotes;


    public function __construct(

        int $inventoryLogId,
        int $inventoryId,
        int $productId,
        int $proSkuId,
        float $ipBoxQuantity,
        float $ipItemQuantity,
        string $logNotes
    )
    {
        $this->inventoryLogId = $inventoryLogId;
        $this->inventoryId    = $inventoryId;
        $this->productId      = $productId;
        $this->proSkuId       = $proSkuId;
        $this->ipBoxQuantity  = $ipBoxQuantity;
        $this->ipItemQuantity = $ipItemQuantity;
        $this->logNotes       = $logNotes;

    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
