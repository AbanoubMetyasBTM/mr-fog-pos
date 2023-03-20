<?php

namespace App\Events\InventoryProducts;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class AddProductToInventory
{
    use Dispatchable, InteractsWithSockets;

    public $inventoryId;
    public $productId;
    public $proSkuId;
    public $ipBoxQuantity;
    public $ipItemQuantity;
    public $limitItemsQty;
    public $logDesc;

    public function __construct(
        int $inventoryId,
        int $productId,
        int $proSkuId,
        int $ipBoxQuantity,
        int $ipItemQuantity,
        int $limitItemsQty,
        string $logDesc
    )
    {
        $this->inventoryId    = $inventoryId;
        $this->productId      = $productId;
        $this->proSkuId       = $proSkuId;
        $this->ipBoxQuantity  = $ipBoxQuantity;
        $this->ipItemQuantity = $ipItemQuantity;
        $this->limitItemsQty  = $limitItemsQty;
        $this->logDesc        = $logDesc;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
