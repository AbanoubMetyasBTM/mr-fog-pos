<?php

namespace App\models\branch;

use App\models\categories_m;
use App\models\ModelUtilities;
use App\models\product\products_m;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class branch_prices_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "branch_prices";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'branch_id', 'pro_id', 'sku_id', 'branch_currency', 'online_item_price',
        'online_box_price', 'item_retailer_price', 'item_wholesaler_price',
        'box_retailer_price', 'box_wholesaler_price', 'is_active'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            branch_prices.*
        "));

        if(isset($attrs["need_branch_join"])){
            $results = $results->addSelect(\DB::raw("
                branches.branch_name,
                product_skus.ps_selected_variant_type_values_text,
                product_skus.ps_selected_variant_type_values,
                ".JsF("products.pro_name","product_name")."
            "))->
            join("product_skus","product_skus.ps_id","=","branch_prices.sku_id")->
            join("products","products.pro_id","=","branch_prices.pro_id")->
            join("branches", "branches.branch_id", "=", "branch_prices.branch_id");
        }

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getBranchPrices($branchId, $attrs=[])
    {
        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        $results = self::select(\DB::raw("
            branch_prices.*,
            product_skus.ps_selected_variant_type_values_text,
            product_skus.ps_box_barcode,
            product_skus.ps_item_barcode,
            product_skus.ps_selected_variant_type_values,
            ".JsF("products.pro_name","product_name")."
        "));

        $results = $results->
        join("product_skus","product_skus.ps_id","=","branch_prices.sku_id")->
        join("products","products.pro_id","=","branch_prices.pro_id")->
        where('branch_prices.branch_id','=', $branchId);

        // filters

        if (isset($attrs["cat_id"]) && $attrs["cat_id"] != 'all' && !empty($attrs["cat_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "products.cat_id = {$attrs["cat_id"]}";
        }

        if (isset($attrs["brand_id"]) && $attrs["brand_id"] != 'all' && !empty($attrs["brand_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "products.brand_id = {$attrs["brand_id"]}";
        }

        if (isset($attrs["pro_id"]) && !empty($attrs["pro_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "products.pro_id = {$attrs["pro_id"]}";
        }

        if (isset($attrs["paginate"]) && !empty($attrs["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attrs["paginate"];
        }


        if (isset($attrs["sku_id"]) && !empty($attrs["sku_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               sku_id = {$attrs['sku_id']}
            ";
        }

        return ModelUtilities::general_attrs($results, $modelUtilitiesAttrs);
    }


    public static function getBranchPriceById($id)
    {
        return self::getData([
            "free_conds" => [
                "id = $id"
            ],
            "return_obj" => "yes"
        ]);
    }

    public static function createBranchPrices($data)
    {
        self::insert($data);
    }


    public static function getBranchesPricesByProductSkusIds($productSkusIds)
    {
        return self::getData([
            "whereIn" => [
                "sku_id" => $productSkusIds
            ]
        ]);

    }

    public static function getBranchesPricesByProductId($productId)
    {
        return self::getData([
            "free_conds" => [
                "branch_prices.pro_id = $productId"
            ],
            "need_branch_join" => true,
        ]);

    }


    public static function getBranchesPricesByProductIds($productIds)
    {
        return self::getData([
            "whereIn" => [
                "pro_id" => $productIds
            ]
        ]);

    }

    public static function getBranchPriceByProductSkuId($branchId, $proSkuId)
    {
        return self::getData([
            "free_conds" => [
                "sku_id = $proSkuId",
                "branch_id = $branchId"
            ],
            "return_obj" => "yes"
        ]);
    }

    public static function getPricesByBranchId($branchId)
    {
        return self::getData([
            "free_conds" => [
                "branch_id = $branchId",
                "is_active = 1"
            ],
        ]);

    }


}
