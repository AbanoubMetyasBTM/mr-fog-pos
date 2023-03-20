<?php


namespace App\btm_form_helpers;


use App\models\inventory\inventories_logs_m;
use App\models\inventory\inventories_products_m;
use App\models\product\products_m;

class InventoryHelper
{

    private static function decreaseInventory($attrs, $log_type){

        $attrs = (object)$attrs;


        $inv_product = inventories_products_m::getInventoryProductByInvIdAndProductSkuId($attrs->inventoryId, $attrs->proSkuId);
        $product     = products_m::getProductById($inv_product->pro_id);


        $new_total_items_qty = calculateProductTotalItemsQty(
            $inv_product->total_items_quantity,
            $product->standard_box_quantity,
            $attrs->ipBoxQuantity,
            $attrs->ipItemQuantity,
            'decrease'
        );

        $new_product_qty = calculateProductBoxesAndItemsAfterUsed(
            $product->standard_box_quantity,
            $inv_product->ip_box_quantity,
            $inv_product->ip_item_quantity,
            $attrs->ipBoxQuantity,
            $attrs->ipItemQuantity
        );

        // decrease product qty from_inv (update qty)
        $data['total_items_quantity'] = $new_total_items_qty;
        $data['ip_box_quantity']      = $new_product_qty['new_boxes_qty'];
        $data['ip_item_quantity']     = $new_product_qty['new_items_qty'];
        inventories_products_m::saveInventoriesProduct($data, $inv_product->ip_id);


        //create inv log
        inventories_logs_m::create([
            'inventory_id'      => $attrs->inventoryId,
            'pro_id'            => $attrs->productId,
            'pro_sku_id'        => $attrs->proSkuId,
            'log_box_quantity'  => $attrs->ipBoxQuantity,
            'log_item_quantity' => $attrs->ipItemQuantity,
            'log_type'          => $log_type,
            'log_operation'     => "decrease",
            'log_desc'          => $attrs->logDesc,
        ]);


    }

    public static function buyProductFromInventory($attrs)
    {

        /*
        $attrs = [
            "inventoryId"    => "",
            "productId"      => "",
            "proSkuId"       => "",
            "ipBoxQuantity"  => "",
            "ipItemQuantity" => "",
            "logDesc"        => "",
        ];
        */

        self::decreaseInventory($attrs, "order");

    }

    public static function addProductToInventory($attrs)
    {

        /*
        $attrs = [
            "inventoryId"    => "",
            "productId"      => "",
            "proSkuId"       => "",
            "ipBoxQuantity"  => "",
            "ipItemQuantity" => "",
            "limitItemsQty"  => "",
            "logDesc"        => "",
        ];
        */

        $attrs = (object)$attrs;

        $inv_product = inventories_products_m::getInventoryProductByInvIdAndProductSkuId(
            $attrs->inventoryId,
            $attrs->proSkuId
        );


        $product = products_m::getProductById($attrs->productId);

        if (is_null($inv_product)) {
            // create
            $new_total_items_qty = calculateProductTotalItemsQty(
                0,
                $product->standard_box_quantity,
                $attrs->ipBoxQuantity,
                $attrs->ipItemQuantity,
                'increase'
            );

            $data['inventory_id']         = $attrs->inventoryId;
            $data['pro_id']               = $attrs->productId;
            $data['pro_sku_id']           = $attrs->proSkuId;
            $data['ip_box_quantity']      = $attrs->ipBoxQuantity;
            $data['ip_item_quantity']     = $attrs->ipItemQuantity;
            $data['ip_item_quantity']     = $attrs->ipItemQuantity;
            $data['quantity_limit']       = $attrs->limitItemsQty;
            $data['total_items_quantity'] = $new_total_items_qty;


            inventories_products_m::saveInventoriesProduct($data);

        }
        else {
            // update
            $new_total_items_qty = calculateProductTotalItemsQty(
                $inv_product->total_items_quantity,
                $product->standard_box_quantity,
                $attrs->ipBoxQuantity,
                $attrs->ipItemQuantity,
                'increase'
            );

            $data['total_items_quantity'] = $new_total_items_qty;
            $data['ip_box_quantity']      = $attrs->ipBoxQuantity + $inv_product->ip_box_quantity;
            $data['ip_item_quantity']     = $attrs->ipItemQuantity + $inv_product->ip_item_quantity;
            inventories_products_m::saveInventoriesProduct($data, $inv_product->ip_id);
        }

        //add inventory log
        inventories_logs_m::create([
            'inventory_id'      => $attrs->inventoryId,
            'pro_id'            => $attrs->productId,
            'pro_sku_id'        => $attrs->proSkuId,
            'log_box_quantity'  => $attrs->ipBoxQuantity,
            'log_item_quantity' => $attrs->ipItemQuantity,
            'log_type'          => 'add_inventory',
            'log_operation'     => "increase",
            'log_desc'          => $attrs->logDesc,
        ]);


    }

    public static function returnOrderFromInventory($attrs)
    {

        $attrs = (object)$attrs;

        self::decreaseInventory($attrs, "return_order");

    }

}
