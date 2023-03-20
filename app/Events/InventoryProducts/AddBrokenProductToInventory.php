<?php

namespace App\Events\InventoryProducts;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class AddBrokenProductToInventory
{
    use Dispatchable, InteractsWithSockets;

    public $inventoryId;
    public $productId;
    public $proSkuId;
    public $ipBoxQuantity;
    public $ipItemQuantity;

    public function __construct(
        int $inventoryId,
        int $productId,
        int $proSkuId,
        int $ipBoxQuantity,
        int $ipItemQuantity
    )
    {
        $this->inventoryId    = $inventoryId;
        $this->productId      = $productId;
        $this->proSkuId       = $proSkuId;
        $this->ipBoxQuantity  = $ipBoxQuantity;
        $this->ipItemQuantity = $ipItemQuantity;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
