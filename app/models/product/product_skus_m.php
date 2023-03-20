<?php

namespace App\models\product;

use App\models\branch\branch_inventory_m;
use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class product_skus_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "product_skus";

    protected $primaryKey = "ps_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'pro_id', 'ps_box_barcode', 'ps_item_barcode', 'ps_box_bought_price', 'ps_item_bought_price',
        'ps_item_retailer_price', 'ps_item_wholesaler_price', 'ps_box_retailer_price', 'ps_box_wholesaler_price',
        'ps_selected_variant_type_values', 'ps_selected_variant_type_values_text',
        'is_active', 'use_default_images', 'ps_img_obj', 'ps_slider',
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            product_skus.*
        "));

        if (isset($attrs["need_product_join"])) {
            $results = $results->addSelect(\DB::Raw("
                products.brand_id,
                products.cat_id,
                products.standard_box_quantity,
                " . JsF("products.pro_name", "product_name") . ",
                " . JsF("brands.brand_name", "brand_name") . ",
                " . JsF("categories.cat_name", "cat_name") . "
            "))->
            join("products", "products.pro_id", "=", "product_skus.pro_id")->
            join('brands', 'brands.brand_id', '=', 'products.brand_id')->
            join('categories', 'categories.cat_id', '=', 'products.cat_id')->
            whereNull('products.deleted_at')->
            where('product_skus.is_active', '=', 1);
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getOnlyActiveSkus()
    {
        $attr["need_product_join"] = true;

        $attr["free_conds"] = [
            "product_skus.is_active = 1"
        ];

        $products = self::getData($attr);

        return productNameBindingWithVariantValueNameHandler($products);
    }

    public static function getProductSkusWithVariantValues($pro_sku_id = null)
    {

        $attr["need_product_join"] = true;

        if (!is_null($pro_sku_id)) {
            $attr["free_conds"] = ["ps_id = $pro_sku_id"];
            $attr["limit"]      = "1";
        }

        $products = self::getData($attr);

        return productNameBindingWithVariantValueNameHandler($products);
    }

    public static function getProductSkus(int $proId): Collection
    {
        return self::getData([
            "free_conds" => [
                "product_skus.pro_id = {$proId}"
            ],
        ]);

    }


    public static function getProductsSkusWithVariantValuesByIds($pro_sku_ids): Collection
    {

        $attr["need_product_join"] = true;
        $attr["whereIn"]           = [
            'ps_id' => $pro_sku_ids
        ];
        $products                  = self::getData($attr);
        return productNameBindingWithVariantValueNameHandler($products);

    }

    public static function checkBarcodeIsDuplicated(string $barcode, $fieldName, int $skuId)
    {

        $row = self::getData([
            "free_conds" => [
                "(ps_box_barcode='${barcode}' OR ps_item_barcode='${barcode}')"
            ],
            "return_obj" => "yes"
        ]);

        if (!is_object($row)) {
            return false;
        }

        if ($row->ps_id != $skuId) {
            return true;
        }

        if ($barcode == $row->{$fieldName}) {
            return false;
        }

        return true;

    }


    public static function getProductByNameOrBarcode($attr)
    {

        $results = self::select(\DB::raw("
            product_skus.*,
            " . JsF("products.pro_name", "product_name") . ",
            CONCAT(
                " . JsFT("products.pro_name") . ",
                ' [ ',
                product_skus.ps_selected_variant_type_values_text,
                ' ]'
            ) as pro_name
        "))->
        join("products", "products.pro_id", "=", "product_skus.pro_id")->
        where("product_skus.is_active", "=", "1")->
        where("products.deleted_at", "=", null);

        $results = $results->whereRaw(\DB::raw("(
            product_skus.ps_box_barcode like '%$attr%' OR
            product_skus.ps_item_barcode like '%$attr%' OR
            products.pro_name like '%$attr%' OR
            product_skus.ps_selected_variant_type_values_text like '%$attr%'
        )"));

        return ModelUtilities::general_attrs($results, []);
    }


    public static function getProductInBranchByNameOrBarcode($branchId, $attr)
    {

        $results = self::select(\DB::raw("
            product_skus.ps_id,
            product_skus.ps_box_barcode,
            product_skus.ps_item_barcode,
            product_skus.ps_selected_variant_type_values_text,
            branch_prices.item_retailer_price,
            branch_prices.item_wholesaler_price,
            branch_prices.box_retailer_price,
            branch_prices.box_wholesaler_price,

            " . JsF("products.pro_name", "product_name") . ",
            CONCAT(
                " . JsFT("products.pro_name") . ",
                ' [ ',
                product_skus.ps_selected_variant_type_values_text,
                ' ]'
            ) as pro_name
        "))->
        join("products", "products.pro_id", "=", "product_skus.pro_id")->
        join('branch_prices', 'branch_prices.sku_id', '=', 'product_skus.ps_id')->

        where('branch_prices.branch_id', '=', $branchId)->
        where("product_skus.is_active", "=", "1")->
        where("branch_prices.is_active", "=", "1")->
        whereNull("products.deleted_at")->
        distinct();

        $results = $results->whereRaw(\DB::raw("(
            product_skus.ps_box_barcode like '%$attr%' OR
            product_skus.ps_item_barcode like '%$attr%' OR
            products.pro_name like '%$attr%' OR
            product_skus.ps_selected_variant_type_values_text like '%$attr%'
        )"));


        return ModelUtilities::general_attrs($results, []);
    }


}
