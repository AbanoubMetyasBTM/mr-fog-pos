<?php

namespace App\Listeners\InventoryLogs;

use App\Events\InventoryLogs\AddInvalidEntryProduct;
use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_products_m;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterAddInvalidEntryProduct implements ShouldQueue
{
    use InteractsWithQueue;
    public function handle(AddInvalidEntryProduct $event)
    {

        $inv_product = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($event->inventoryId, $event->proSkuId);


        \DB::beginTransaction();


        // update (decrease product qty)
        $data['ip_box_quantity']  =  $inv_product->ip_box_quantity - $event->ipBoxQuantity;
        $data['ip_item_quantity'] =  $inv_product->ip_item_quantity - $event->ipItemQuantity;
        inventories_products_m::saveInventoriesProduct($data, $inv_product->ip_id);


        // add invalid log
        inventories_logs_m::create([
            'inventory_id'      => $event->inventoryId,
            'pro_id'            => $event->productId,
            'pro_sku_id'        => $event->proSkuId,
            'log_box_quantity'  => $event->ipBoxQuantity,
            'log_item_quantity' => $event->ipItemQuantity,
            'log_type'          => 'invalid_entry',
            'log_operation'     => "decrease",
            'log_desc'          => "This invalid entry for log id {$event->inventoryLogId}, " . $event->logNotes,
        ]);


        // update (is_refunded) log in request
        inventories_logs_m::where('id', $event->inventoryLogId)->update([
            "is_refunded" => 1
        ]);

        \DB::commit();
    }


}
