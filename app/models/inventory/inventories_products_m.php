<?php

namespace App\models\inventory;

use App\models\categories_m;
use App\models\ModelUtilities;
use App\models\product\products_m;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class inventories_products_m extends \Eloquent
{

    use SoftDeletes, InventoryProductsReportsTrait;

    protected $table = "inventory_products";

    protected $primaryKey = "ip_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'inventory_id', 'pro_id', 'pro_sku_id',
        'ip_box_quantity', 'ip_item_quantity',
        'total_items_quantity', 'quantity_limit'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            inventory_products.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function checkIfInventoryHasProducts($inventory_id): bool
    {
        $inv_products =
            self::query()
                ->where('inventory_id', '=', $inventory_id)
                ->limit(1)
                ->get();

        if (!count($inv_products)) {
            return false;
        }
        return true;

    }

    public static function saveInventoriesProduct($data, $ipId = null)
    {

        if (is_null($ipId)) {
            return self::create([
                "inventory_id"         => $data['inventory_id'],
                "pro_id"               => $data['pro_id'],
                "pro_sku_id"           => $data['pro_sku_id'],
                "ip_box_quantity"      => $data['ip_box_quantity'],
                "ip_item_quantity"     => $data['ip_item_quantity'],
                "quantity_limit"       => $data['quantity_limit'] ?? 0,
                "total_items_quantity" => $data['total_items_quantity'],
            ]);
        }

        return self::where('ip_id', '=', $ipId)->
        update(array(
            "ip_box_quantity"      => $data['ip_box_quantity'],
            "ip_item_quantity"     => $data['ip_item_quantity'],
            "total_items_quantity" => $data['total_items_quantity'],

        ));

    }

    public static function getInventoryProductByInvIdAndProductSkuId($invId, $productSkuId)
    {
        return self::getData([
            "free_conds" => [
                "inventory_id = $invId",
                "pro_sku_id = $productSkuId"
            ],
            "return_obj" => "yes"
        ]);

    }

    private static function getInventoriesProductsConds(array $attr = []): array
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        //
        if (isset($attr["inventory_id"]) && $attr["inventory_id"] != 'all' && !empty($attr["inventory_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               inventory_id = {$attr['inventory_id']}
            ";
        }

        if (isset($attr["inventory_ids"])) {
            $modelUtilitiesAttrs["whereIn"]["inventory_id"] = $attr["inventory_ids"];
        }


        if (isset($attr["quantity_limit"]) && $attr["quantity_limit"] != 'all' && !empty($attr["quantity_limit"])) {

            if ($attr["quantity_limit"] == 'yes') {
                $modelUtilitiesAttrs["free_conds"][] = "
                    total_items_quantity < quantity_limit
                ";
            }
            else {
                $modelUtilitiesAttrs["free_conds"][] = "
                    total_items_quantity > quantity_limit
                ";
            }

        }


        if (isset($attr["cat_id"]) && $attr["cat_id"] != 'all' && !empty($attr["cat_id"])) {

            $attr["cat_id"] = collect($attr["cat_id"])->toArray();
            $productsIds = collect(products_m::getProductsByCatIds($attr["cat_id"]))->pluck('pro_id');
            $modelUtilitiesAttrs["whereIn"] = ["inventory_products.pro_id" => $productsIds];
        }

        if (isset($attr["brand_id"]) && $attr["brand_id"] != 'all' && !empty($attr["brand_id"])) {

            $productsIds = collect(products_m::getProductsByBrandId($attr["brand_id"])->pluck('pro_id'));
            $modelUtilitiesAttrs["whereIn"] = ["inventory_products.pro_id" => $productsIds];
        }


        if (isset($attr["sku_id"]) && !empty($attr["sku_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               pro_sku_id = {$attr['sku_id']}
            ";
        }




        return $modelUtilitiesAttrs;

    }

    public static function getInventoriesProducts($attr = []): Collection
    {

        $results = self::select(\DB::raw("
            inventory_products.*,
            products.brand_id,
            products.cat_id,
            inventory_places.inv_name,
            product_skus.ps_selected_variant_type_values_text,
            product_skus.ps_selected_variant_type_values,
            " . JsF("products.pro_name", "product_name") . ",
            " . JsF("brands.brand_name", "brand_name") . ",
            " . JsF("categories.cat_name", "cat_name") . "

        "));

        $results = $results->
        join("inventory_places", "inventory_places.inv_id", "=", "inventory_products.inventory_id")->
        join('products', 'products.pro_id', '=', 'inventory_products.pro_id')->
        join('brands','brands.brand_id','=', 'products.brand_id')->
        join('categories','categories.cat_id','=', 'products.cat_id')->
        join('product_skus', 'product_skus.ps_id', '=', 'inventory_products.pro_sku_id')->
        where('products.deleted_at', '=', null)->
        orderBy("inventory_products.ip_id", "desc");


        return ModelUtilities::general_attrs($results, self::getInventoriesProductsConds($attr));

    }

    public static function checkIfProductExistAtAnyInventory(int $proId): bool
    {

        $res = self::getData([
            "free_conds" => [
                "pro_id = $proId"
            ],
            "limit"      => "1",
            "return_obj" => "yes"
        ]);

        if(is_object($res)){
            return true;
        }

        return false;

    }

    public static function getInventoryProductByInvIdAndProductsSkuIds($invId, $productsSkuIds)
    {
        return self::getData([
            "free_conds" => [
                "inventory_id = $invId",
            ],
            "whereIn" => [
                "pro_sku_id" => $productsSkuIds
            ]

        ]);

    }

    public static function checkItHasAtLeastOneRow(): bool
    {
        $res = self::limit(1)->first();
        if(is_object($res)){
            return true;
        }
        return false;
    }

}
