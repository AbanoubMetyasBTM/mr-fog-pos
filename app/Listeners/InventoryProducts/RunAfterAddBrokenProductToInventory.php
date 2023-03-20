<?php

namespace App\Listeners\InventoryProducts;

use App\Events\InventoryProducts\AddBrokenProductToInventory;
use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_products_m;
use App\models\product\products_m;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterAddBrokenProductToInventory implements ShouldQueue
{
    use InteractsWithQueue;
    public function handle(AddBrokenProductToInventory $event)
    {

        //TODO mets

        \DB::beginTransaction();


        $inv_product = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($event->inventoryId, $event->proSkuId);
        $product     = products_m::getProductById($inv_product->pro_id);


        $new_total_items_qty = calculateProductTotalItemsQty(
            $inv_product->total_items_quantity,
            $product->standard_box_quantity,
            $event->ipBoxQuantity,
            $event->ipItemQuantity,
            'decrease'
        );

        $new_product_qty = calculateProductBoxesAndItemsAfterUsed(
            $product->standard_box_quantity,
            $inv_product->ip_box_quantity,
            $inv_product->ip_item_quantity,
            $event->ipBoxQuantity,
            $event->ipItemQuantity
        );

        // decrease product qty from_inv (update qty)
        $data['total_items_quantity'] = $new_total_items_qty;
        $data['ip_box_quantity']      = $new_product_qty['new_boxes_qty'];
        $data['ip_item_quantity']     = $new_product_qty['new_items_qty'];
        inventories_products_m::saveInventoriesProduct($data, $inv_product->ip_id);


        //create inv log
        inventories_logs_m::create([
            'inventory_id'      => $event->inventoryId,
            'pro_id'            => $event->productId,
            'pro_sku_id'        => $event->proSkuId,
            'log_box_quantity'  => $event->ipBoxQuantity,
            'log_item_quantity' => $event->ipItemQuantity,
            'log_type'          => 'broken_products',
            'log_operation'     => "decrease",
            'log_desc'          => 'add broken products',
        ]);

        \DB::commit();
    }


}
