<?php

namespace App\Listeners\InventoryProducts;

use App\btm_form_helpers\InventoryHelper;
use App\Events\InventoryProducts\AddProductToInventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterAddProductToInventory implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AddProductToInventory $event)
    {

        //TODO mets

        \DB::beginTransaction();

        InventoryHelper::addProductToInventory([
            "inventoryId"    => $event->inventoryId,
            "productId"      => $event->productId,
            "proSkuId"       => $event->proSkuId,
            "ipBoxQuantity"  => $event->ipBoxQuantity,
            "ipItemQuantity" => $event->ipItemQuantity,
            "limitItemsQty"  => $event->limitItemsQty,
            "logDesc"        => $event->logDesc,
        ]);


        \DB::commit();

    }


}
