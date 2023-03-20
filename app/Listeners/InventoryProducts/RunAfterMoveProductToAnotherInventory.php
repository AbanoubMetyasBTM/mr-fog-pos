<?php

namespace App\Listeners\InventoryProducts;

use App\Events\InventoryProducts\MoveProductToInventory;
use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_products_m;
use App\models\product\products_m;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterMoveProductToAnotherInventory implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(MoveProductToInventory $event)
    {

        //TODO mets

        \DB::beginTransaction();

        $from_inv = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($event->fromInventoryId, $event->proSkuId);
        $product = products_m::getProductById($from_inv->pro_id);


        // decrease product qty from_inv (update qty)
        $new_total_items_qty = calculateProductTotalItemsQty(
            $from_inv->total_items_quantity,
            $product->standard_box_quantity,
            $event->ipBoxQuantity,
            $event->ipItemQuantity,
            'decrease'
        );


        $new_product_qty = calculateProductBoxesAndItemsAfterUsed(
            $product->standard_box_quantity,
            $from_inv->ip_box_quantity,
            $from_inv->ip_item_quantity,
            $event->ipBoxQuantity,
            $event->ipItemQuantity
        );

        $data['total_items_quantity'] = $new_total_items_qty;
        $data['ip_box_quantity']      = $new_product_qty['new_boxes_qty'];
        $data['ip_item_quantity']     = $new_product_qty['new_items_qty'];
        inventories_products_m::saveInventoriesProduct($data, $from_inv->ip_id);


        //create inv log
        inventories_logs_m::create([
            'inventory_id'      => $event->fromInventoryId,
            'pro_id'            => $event->productId,
            'pro_sku_id'        => $event->proSkuId,
            'log_box_quantity'  => $event->ipBoxQuantity,
            'log_item_quantity' => $event->ipItemQuantity,
            'log_type'          => 'transfer_to_another_decrease',
            'log_operation'     => "decrease",
            'log_desc'          => 'move product to another inventory',
        ]);


        $to_inv = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($event->toInventoryId, $event->proSkuId);


        // increase to_inv (create or update)
        if (!is_object($to_inv)) {
            // create

            $new_total_items_qty = calculateProductTotalItemsQty(
                0,
                $product->standard_box_quantity,
                $event->ipBoxQuantity,
                $event->ipItemQuantity,
                'increase'
            );

            $data['inventory_id']         = $event->toInventoryId;
            $data['pro_id']               = $event->productId;
            $data['pro_sku_id']           = $event->proSkuId;
            $data['ip_box_quantity']      = $event->ipBoxQuantity;
            $data['ip_item_quantity']     = $event->ipItemQuantity;
            $data['quantity_limit']       = $from_inv->quantity_limit;
            $data['total_items_quantity'] = $new_total_items_qty;
            inventories_products_m::saveInventoriesProduct($data);
        }
        else {
            // update

            $new_total_items_qty = calculateProductTotalItemsQty(
                $to_inv->total_items_quantity,
                $product->standard_box_quantity,
                $event->ipBoxQuantity,
                $event->ipItemQuantity,
                'increase'
            );

            $data['total_items_quantity'] = $new_total_items_qty;
            $data['ip_box_quantity']      = $event->ipBoxQuantity + $to_inv->ip_box_quantity;
            $data['ip_item_quantity']     = $event->ipItemQuantity + $to_inv->ip_item_quantity;
            inventories_products_m::saveInventoriesProduct($data, $to_inv->ip_id);
        }

        //create inv log
        inventories_logs_m::create([
            'inventory_id'      => $event->toInventoryId,
            'pro_id'            => $event->productId,
            'pro_sku_id'        => $event->proSkuId,
            'log_box_quantity'  => $event->ipBoxQuantity,
            'log_item_quantity' => $event->ipItemQuantity,
            'log_type'          => 'transfer_to_another_increase',
            'log_operation'     => "increase",
            'log_desc'          => 'add product to inventory',
        ]);

        \DB::commit();
    }


}
