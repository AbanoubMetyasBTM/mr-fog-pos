<?php


namespace App\models\inventory;


use Illuminate\Support\Facades\DB;

trait InventoryProductsReportsTrait
{

    private static function conditionQuantitiesAtInventories($attrs, $results)
    {

        $attrs['limit']        = Vsi($attrs['limit'] ?? "");
        $attrs['branch_id']    = Vsi($attrs['branch_id'] ?? "");
        $attrs['inventory_id'] = Vsi($attrs['inventory_id'] ?? "");
        $attrs['cat_id']       = Vsi($attrs['cat_id'] ?? "");
        $attrs['brand_id']     = Vsi($attrs['brand_id'] ?? "");


        if (!empty($attrs['limit'])) {
            $results = $results->
            limit($attrs['limit']);
        }

        if (!empty($attrs['branch_id']) && $attrs['branch_id'] != 'all') {
            $results = $results->
            where('branches.branch_id', '=', $attrs['branch_id']);
        }

        if (!empty($attrs['inventory_id']) && $attrs['inventory_id'] != 'all') {
            $results = $results->
            where('branch_inventory.inventory_id', '=', $attrs['inventory_id']);
        }

        if (!empty($attrs['cat_id']) && $attrs['cat_id'] != 'all') {
            $results = $results->
            where('products.cat_id', '=', $attrs['cat_id']);
        }

        if (!empty($attrs['brand_id']) && $attrs['cat_id'] != 'all') {
            $results = $results->
            where('products.brand_id', '=', $attrs['brand_id']);
        }

        return $results;

    }

    public static function getCriticalQuantities($attrs = [])
    {

        $getCriticalQuantities = self::
        select(\DB::raw("
            inventory_places.inv_name,
            branches.branch_name,
            product_skus.ps_selected_variant_type_values_text,
            " . JsF("products.pro_name") . ",
            product_skus.ps_selected_variant_type_values_text,
            inventory_products.total_items_quantity,
            inventory_products.quantity_limit
        "))->
        whereRaw(DB::raw("
            inventory_products.quantity_limit >= inventory_products.total_items_quantity
        "))->
        join('inventory_places', 'inventory_places.inv_id', '=', 'inventory_products.inventory_id')->
        join('product_skus', 'product_skus.ps_id', '=', 'inventory_products.pro_sku_id')->
        join('products', 'products.pro_id', '=', 'product_skus.pro_id')->
        leftJoin('branch_inventory', 'branch_inventory.inventory_id', '=', 'inventory_products.inventory_id')->
        leftJoin('branches', 'branches.branch_id', '=', 'branch_inventory.branch_id');

        $getCriticalQuantities = self::conditionQuantitiesAtInventories($attrs, $getCriticalQuantities);

        $getCriticalQuantities = $getCriticalQuantities->groupBy("inventory_products.inventory_id", "inventory_products.pro_sku_id");


        return $getCriticalQuantities->get();

    }

    public static function reportGetInventoryQuantitiesGroupedByCategory()
    {
        return self::query()->
        select(\DB::raw("
            concat (
              inventory_places.inv_name,
              ' - ',
              " . JsFT("categories.cat_name") . "
            ) as item_name,
            SUM(inventory_products.total_items_quantity) as total_items_quantity

        "))->
        join('inventory_places', 'inventory_places.inv_id', '=', 'inventory_products.inventory_id')->
        join('products', 'products.pro_id', '=', 'inventory_products.pro_id')->
        join('categories', 'categories.cat_id', '=', 'products.cat_id')->
        orderBy(DB::raw("
            SUM(inventory_products.total_items_quantity)
        "), 'desc')->
        groupBy('products.cat_id')->
        get();
    }

    public static function reportGetInventoryQuantitiesGroupedByBrand()
    {
        return self::query()->
        select(\DB::raw("
            concat (
              inventory_places.inv_name,
              ' - ',
              " . JsFT("brands.brand_name") . "
            ) as item_name,
            SUM(inventory_products.total_items_quantity) as total_items_quantity

        "))->
        join('inventory_places', 'inventory_places.inv_id', '=', 'inventory_products.inventory_id')->
        join('products', 'products.pro_id', '=', 'inventory_products.pro_id')->
        join('brands', 'brands.brand_id', '=', 'products.brand_id')->
        orderBy(DB::raw("
            SUM(inventory_products.total_items_quantity)
        "), 'desc')->
        groupBy('products.cat_id')->
        get();
    }

}
