<?php

namespace App\models\product;

use App\form_builder\ProductsBuilder;
use App\models\categories_m;
use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class products_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "products";

    protected $primaryKey = "pro_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'cat_id', 'brand_id', 'pro_name', 'pro_img_obj',
        'pro_slider', 'pro_desc', 'standard_box_quantity'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            products.*,
            brands.brand_name,
            products.cat_id,
            " . JsF("categories.cat_name", "cat_name") . ",
            " . JsF("brands.brand_name", "brand_name") . ",
            " . JsF("categories.cat_name", "cat_name") . "
        "));

        $results = ModelUtilities::getTranslateData($results, "products", new ProductsBuilder());

        $results = $results->
        join("categories", "categories.cat_id", "=", "products.cat_id")->
        join("brands", "brands.brand_id", "=", "products.brand_id");

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllProducts(array $attrs = [])
    {
        return self::getData(self::getAllProductsConds($attrs));
    }

    public static function getProductById(int $productId)
    {
        return self::getData([
            "free_conds" => [
                "products.pro_id = $productId"
            ],
            "return_obj" => "yes"
        ]);
    }

    public static function getProductsByCatIds($catIds): Collection
    {
        $attrs['whereIn'] = ['products.cat_id' => $catIds];
        return self::getData($attrs);
    }

    public static function getProductsByBrandId($brandId): Collection
    {
        return self::getData([
            "free_conds" => [
                "products.brand_id = $brandId"
            ]
        ]);
    }

    private static function getAllProductsConds($attr = [])
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        if (isset($attr["paginate"]) && !empty($attr["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attr["paginate"];
        }


        if (isset($attr["cat_id"]) && $attr["cat_id"] != 'all' && !empty($attr["cat_id"])) {

            $modelUtilitiesAttrs["free_conds"][] = "
               products.cat_id = {$attr['cat_id']}
            ";
        }

        if (isset($attr["brand_id"]) && $attr["brand_id"] != 'all' && !empty($attr["brand_id"])) {

            $modelUtilitiesAttrs["free_conds"][] = "
               products.brand_id = {$attr['brand_id']}
            ";
        }
        return $modelUtilitiesAttrs;

    }

    public static function getProductByIds($productIds)
    {
        return self::getData([
            "whereIn" => [
                "pro_id" => $productIds
            ]

        ]);
    }

    public static function getProductByName($productName)
    {

        $productName = Vsi($productName);

        return self::getData([
            "free_conds" => [
                "products.pro_name like '%$productName%'"
            ],
        ]);

    }

}
