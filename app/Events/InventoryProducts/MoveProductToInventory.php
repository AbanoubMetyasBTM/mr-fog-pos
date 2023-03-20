<?php

namespace App\Events\InventoryProducts;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class MoveProductToInventory
{
    use Dispatchable, InteractsWithSockets;

    public $fromInventoryId;
    public $toInventoryId;
    public $productId;
    public $proSkuId;
    public $ipBoxQuantity;
    public $ipItemQuantity;

    public function __construct(
        int $fromInventoryId,
        int $toInventoryId,
        int $productId,
        int $proSkuId,
        int $ipBoxQuantity,
        int $ipItemQuantity
    )
    {
        $this->fromInventoryId = $fromInventoryId;
        $this->toInventoryId   = $toInventoryId;
        $this->productId       = $productId;
        $this->proSkuId        = $proSkuId;
        $this->ipBoxQuantity   = $ipBoxQuantity;
        $this->ipItemQuantity  = $ipItemQuantity;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
