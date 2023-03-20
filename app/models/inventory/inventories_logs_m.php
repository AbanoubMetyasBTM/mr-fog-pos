<?php

namespace App\models\inventory;

use App\models\categories_m;
use App\models\ModelUtilities;
use App\models\product\products_m;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class inventories_logs_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "inventory_log";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'inventory_id', 'pro_id', 'pro_sku_id', 'log_box_quantity', 'log_item_quantity',
        'log_type', 'log_operation', 'log_desc', 'is_refunded'
    ];

    public static $invLogTypes = [
        "order"                        => "Order",
        "transfer_to_another_increase" => "Received From Another Inventory",
        "transfer_to_another_decrease" => "Moved To Another Inventory",
        "broken_products"              => "Broken Product",
        "invalid_entry"                => "Invalid Entry",
        "add_inventory"                => "Add Product",
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            inventory_log.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function checkIfInventoryHasLogs($inventory_id): bool
    {
        $inv_logs =
            self::query()
                ->where('inventory_id', '=', $inventory_id)
                ->get();

        if (!count($inv_logs)) {
            return false;
        }
        return true;

    }


    private static function getInventoriesLogsConds(array $attr = []): array
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        // filters
        if (isset($attr["inventory_id"]) && !empty($attr["inventory_id"]) && $attr["inventory_id"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               inventory_id = {$attr['inventory_id']}
            ";
        }

        if (isset($attr["inventory_ids"])) {
            $modelUtilitiesAttrs["whereIn"]["inventory_id"] = $attr["inventory_ids"];
        }

        if (isset($attr["pro_sku_id"]) && !empty($attr["pro_sku_id"]) && $attr["pro_sku_id"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               pro_sku_id = {$attr['pro_sku_id']}
            ";
        }

        if (isset($attr["log_type"]) && !empty($attr["log_type"]) && $attr["log_type"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               log_type = '{$attr["log_type"]}'
            ";
        }

        if (isset($attr["log_id"]) && !empty($attr["log_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
                id = {$attr["log_id"]}
            ";
        }


        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               inventory_log.created_at > {$attr["date_from"]}
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               inventory_log.created_at < {$attr["date_to"]}
            ";
        }


        if (isset($attr["cat_id"]) && $attr["cat_id"] != 'all' && !empty($attr["cat_id"])) {
            $attr["cat_id"] = collect($attr["cat_id"])->toArray();
            $productsIds = collect(products_m::getProductsByCatIds($attr["cat_id"]))->pluck('pro_id');
            $modelUtilitiesAttrs["whereIn"] = ["inventory_log.pro_id" => $productsIds];
        }

        if (isset($attr["brand_id"]) && $attr["brand_id"] != 'all' && !empty($attr["brand_id"])) {
            $productsIds = collect(products_m::getProductsByBrandId($attr["brand_id"])->pluck('pro_id'));
            $modelUtilitiesAttrs["whereIn"] = ["inventory_log.pro_id" => $productsIds];
        }

        if (isset($attr["sku_id"]) && !empty($attr["sku_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               pro_sku_id = {$attr['sku_id']}
            ";
        }



        return $modelUtilitiesAttrs;

    }

    public static function getInventoriesLogs(array $attr = []): Collection
    {

        $results = self::select(\DB::raw("
            inventory_log.*,
            inventory_places.inv_name,

            product_skus.ps_selected_variant_type_values,
            concat(
                " . JsFT("products.pro_name") . ",
                ' [ ',
                product_skus.ps_selected_variant_type_values_text,
                ' ] '
            ) as 'product_name'
        "));

        $results = $results->
        join("inventory_places", "inventory_places.inv_id", "=", "inventory_log.inventory_id")->
        join('products', 'products.pro_id', '=', 'inventory_log.pro_id')->
        join('product_skus', 'product_skus.ps_id', '=', 'inventory_log.pro_sku_id')->
        orderBy("inventory_log.id", "desc");

        return ModelUtilities::general_attrs($results, self::getInventoriesLogsConds($attr));

    }


    public static function getInventoryLogByLogId($invLogId)
    {
        return self::getData([
            "free_conds" => [
                "id = $invLogId",
            ],
            "return_obj" => "yes",
        ]);

    }
}
