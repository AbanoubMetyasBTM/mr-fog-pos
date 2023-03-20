<?php


namespace App\btm_form_helpers;

use App\models\branch\branch_prices_m;
use App\models\product\product_skus_m;
use App\models\supplier\supplier_order_items_m;
use App\models\supplier\suppliers_m;
use Illuminate\Http\Request;

class AddSupplierOrderHelper
{

    public static function checkIfPriceOfSupplierProductGreaterThanMainProductPrice($orderItemsData)
    {
        // check if product price less than basic product price !!

        $orderItemsData = collect($orderItemsData);
        $productSkusIds = $orderItemsData->pluck("pro_sku_id");

        $productsBasicPrices = collect(product_skus_m::getProductsSkusWithVariantValuesByIds($productSkusIds));

        $links = "";
        foreach ($productsBasicPrices as $productPrices) {
            $supplierProductPrices = $orderItemsData->where("pro_sku_id", "=", $productPrices['ps_id'])->first();
            if (
                $productPrices->{"ps_" . $supplierProductPrices["item_type"] . "_bought_price"} < $supplierProductPrices["item_total_cost"]
            ) {

                $url   = url("admin/products-sku/show/" . $productPrices->pro_id . "/" . $productPrices->ps_id);
                $links .= "<br><a href='$url'  target='_blank'>Edit Product prices</a>";
            }
        }

        return $links;
    }

    public static function checkIfPriceOfSupplierProductGreaterThanBranchesPrices($orderItemsData)
    {
        // check if product price less than branch price !!
        $orderItemsData        = collect($orderItemsData);
        $productSkusIds        = $orderItemsData->pluck("pro_id");
        $productBranchesPrices = collect(branch_prices_m::getBranchesPricesByProductIds($productSkusIds));

        $links = "";

        if (!empty($productBranchesPrices)) {
            $productIdsThatHavePricesLessThanSupplierProduct = [];
            foreach ($productBranchesPrices as $branchPrices) {
                $productSku = $orderItemsData->where("pro_id", "=", $branchPrices['pro_id'])->first();

                if (
                    floatval($branchPrices->{"online_" . $productSku["item_type"] . "_price"}) < $productSku["item_total_cost"] ||
                    floatval($branchPrices->{$productSku["item_type"] . "_retailer_price"}) < $productSku["item_total_cost"] ||
                    floatval($branchPrices->{$productSku["item_type"] . "_wholesaler_price"}) < $productSku["item_total_cost"]
                ) {
                    $productIdsThatHavePricesLessThanSupplierProduct[] = $branchPrices['pro_id'];
                }
            }

            $productIdsThatHavePricesLessThanSupplierProduct = collect($productIdsThatHavePricesLessThanSupplierProduct)->unique();
            foreach ($productIdsThatHavePricesLessThanSupplierProduct as $productId) {
                $url   = url("admin/branches-prices/show-product-branches-prices/" . $productId);
                $links .= "<br><a href='$url'  target='_blank'>Edit branch products prices</a>";
            }
        }

        return $links;
    }

    public static function checkAllItemsPrices(Request $request)
    {

        $productSkus               = product_skus_m::getProductsSkusWithVariantValuesByIds($request->get("product_sku"));
        $orderItems                = [];

        foreach ($productSkus->all() as $key=>$productSku){
            $productSku->item_type       = $request->get("item_type")[$key];
            $productSku->pro_sku_id      = $productSku->ps_id;
            $productSku->item_total_cost = $request->get("item_total_cost")[$key];

            $orderItems[] = $productSku;
        }


        $productsPrices = AddSupplierOrderHelper::checkIfPriceOfSupplierProductGreaterThanMainProductPrice($orderItems);
        $branchesPrices = AddSupplierOrderHelper::checkIfPriceOfSupplierProductGreaterThanBranchesPrices($orderItems);

        $msg = "Order is processing now";
        if (!empty($productsPrices)) {
            $msg .= "<br> Please edit this products prices $productsPrices";
        }

        if (!empty($branchesPrices)) {
            $msg .= "<br> Please edit this branches products prices $branchesPrices";
        }

        return $msg;
    }

    public static function afterAddDoneSupplierOrder(Request $request, $orderObj, $orderItemsData)
    {

        // add products (items) to inv
        // add product inv log
        $supplier = suppliers_m::getSupplierDataById($request->get("supplier_id"));

        foreach ($orderItemsData as $item) {

            if ($item["item_type"] == "box") {
                $ipBoxQty  = $item["order_quantity"];
                $ipItemQty = 0;
            }
            else {
                $ipBoxQty  = 0;
                $ipItemQty = $item["order_quantity"];
            }

            InventoryHelper::addProductToInventory([
                "inventoryId"    => $item["inventory_id"],
                "productId"      => $item["pro_id"],
                "proSkuId"       => $item["pro_sku_id"],
                "ipBoxQuantity"  => (int)$ipBoxQty,
                "ipItemQuantity" => (int)$ipItemQty,
                "limitItemsQty"  => 0,
                "logDesc"        => "add products to inventory, this products from supplier ({$supplier->sup_name}) of order ({$orderObj->supplier_order_id})",
            ]);

        }

        // add money to supplier wallet
        // add money log

        $paidAmount          = floatval($request->get("paid_amount"));
        $totalItemsCost      = $orderObj->total_items_cost;
        $addedMoneyAmount    = $totalItemsCost - $paidAmount;


        if ($addedMoneyAmount > 0) {

            $depositNotes = "
                {$addedMoneyAmount} is added to supplier wallet,
                related to order id - #{$orderObj->supplier_order_id}
                we paid to him ({$paidAmount}) of total cost ({$totalItemsCost}) and remain ({$addedMoneyAmount})
            ";

            WalletHelper::depositMoney([
                "moneyAmount"          => $addedMoneyAmount,
                "walletId"             => $supplier->wallet_id,
                "walletOwnerName"      => $supplier->sup_name,
                "notes"                => $depositNotes,
                "transactionCurrency"  => $supplier->sup_currency,
            ]);

        }

    }


    public static function orderCostHandler(Request $request)
    {

        $total_items_cost = 0;

        foreach ($request->get("product_sku") as $key => $productSkuId) {
            $order_quantity   = intval($request->get("order_quantity")[$key]);
            $item_total_cost  = floatval($request->get("item_total_cost")[$key]);
            $item_total_cost  = floatval($item_total_cost) * $order_quantity;
            $total_items_cost = $total_items_cost + $item_total_cost;
        }

        return [
            "total_items_cost" => $total_items_cost,
            "total_cost"       => $total_items_cost + floatval($request->get("additional_fees"))
        ];

    }

    public static function orderItemsHandler(Request $request, $orderId)
    {
        $productSkus               = product_skus_m::getProductsSkusWithVariantValuesByIds($request->get("product_sku"));
        $items                     = [];

        foreach ($request->get("product_sku") as $key => $productSkuId) {
            $productSkuObj = $productSkus->where("ps_id", "=", $productSkuId)->first();

            $items[$key]["inventory_id"]      = $request->get("inv_id")[$key];
            $items[$key]["pro_id"]            = $productSkuObj->pro_id;
            $items[$key]["pro_sku_id"]        = $productSkuId;
            $items[$key]["item_type"]         = $request->get("item_type")[$key];
            $items[$key]["order_quantity"]    = intval($request->get("order_quantity")[$key]);
            $items[$key]["item_total_cost"]   = floatval($request->get("item_total_cost")[$key]);
            $items[$key]["total_items_cost"]  = floatval($items[$key]["item_total_cost"]) * $items[$key]["order_quantity"];
            $items[$key]["operation_type"]    = "buy";
            $items[$key]["supplier_order_id"] = $orderId;
            $items[$key]["created_at"]        = now();
        }

        return $items;
    }

    public static function calculateTotalItemsCostReturnOrderItems(Request $request,$allItemsData){

        $total_items_cost=0;

        foreach ($request->get("item_ids") as $key => $itemId) {

            if (empty($request->get("want_return_qty")[$key])) {
                continue;
            }

            $itemData         = $allItemsData->where("id", "=", $itemId)->first();
            $total_items_cost +=
                floatval($request->get("want_return_qty")[$key]) *
                floatval($itemData->item_total_cost);

        }

        return $total_items_cost;

    }

    public static function returnOrderItemsQuantityValidation(Request $request, $allItemsData, $orderObj, $supplierData)
    {

        foreach ($request->get("item_ids") as $key => $itemId) {

            if (empty($request->get("want_return_qty")[$key])) {
                continue;
            }

            $itemData    = $allItemsData->where("id", "=", $itemId)->first();
            $returnedQty = $allItemsData->where("pro_sku_id", "=", $itemData->pro_sku_id)->
            where("item_type", "=", $itemData->item_type)->
            where("operation_type", "=", "return")->
            pluck("order_quantity")->sum();


            $availableItemToReturn = $itemData->order_quantity - $returnedQty;

            if (intval($request->get("want_return_qty")[$key]) > $availableItemToReturn) {
                return [
                    "error" => "The quantity returned is greater than the quantity available for return"
                ];
            }
        }

        return true;

    }

    public static function returnOrderItems(Request $request, $allItemsData, $orderObj, $supplierData)
    {

        foreach ($request->get("item_ids") as $key => $itemId) {


            if (empty($request->get("want_return_qty")[$key])) {
                continue;
            }

            $itemData    = $allItemsData->where("id", "=", $itemId)->first();
            $returnedQty = $allItemsData->where("pro_sku_id", "=", $itemData->pro_sku_id)->
            where("item_type", "=", $itemData->item_type)->
            where("operation_type", "=", "return")->
            pluck("order_quantity")->sum();

            $availableItemToReturn = $itemData->order_quantity - $returnedQty;


            if (intval($request->get("want_return_qty")[$key]) > $availableItemToReturn) {
                return [
                    "error" => "The quantity returned is greater than the quantity available for return"
                ];
            }

            $data[$key]["operation_type"]    = "return";
            $data[$key]["supplier_order_id"] = $itemData->supplier_order_id;
            $data[$key]["inventory_id"]      = $itemData->inventory_id;
            $data[$key]["pro_id"]            = $itemData->pro_id;
            $data[$key]["pro_sku_id"]        = $itemData->pro_sku_id;
            $data[$key]["item_type"]         = $itemData->item_type;
            $data[$key]["item_total_cost"]   = $itemData->item_total_cost;
            $data[$key]["order_quantity"]    = $request->get("want_return_qty")[$key];
            $data[$key]["created_at"]        = now();
            $data[$key]["total_items_cost"]  = floatval($data[$key]["order_quantity"]) * floatval($itemData->item_total_cost);
            $data[$key]["total_items_cost"]  = round($data[$key]["total_items_cost"], 2);

            if ($data[$key]["item_type"] == "box") {
                $ipBoxQty  = $data[$key]["order_quantity"];
                $ipItemQty = 0;
            }
            else {
                $ipBoxQty  = 0;
                $ipItemQty = $data[$key]["order_quantity"];
            }

            //minus items form inventories and add logs
            InventoryHelper::returnOrderFromInventory([
                "inventoryId"    => $data[$key]["inventory_id"],
                "productId"      => $data[$key]["pro_id"],
                "proSkuId"       => $data[$key]["pro_sku_id"],
                "ipBoxQuantity"  => $ipBoxQty,
                "ipItemQuantity" => $ipItemQty,
                "logDesc"        => "return order items of order ({$orderObj->supplier_order_id}) to supplier ($supplierData->sup_name)",
            ]);


        }

        supplier_order_items_m::insert($data);

    }

}
