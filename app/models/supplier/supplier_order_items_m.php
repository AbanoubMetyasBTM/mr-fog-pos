<?php

namespace App\models\supplier;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;

class supplier_order_items_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "supplier_order_items";

    protected $primaryKey = "id";

    protected $dates = ["deleted_ate"];

    protected $fillable = [
       'operation_type', 'supplier_order_id', 'inventory_id',
       'pro_id', 'pro_sku_id', 'item_type', 'order_quantity',
       'item_cost', 'item_tax', 'item_total_cost', 'total_items_cost'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            supplier_order_items.*
        "));


        if(isset($attrs["need_product_join"])){
            $results = $results->addSelect(\DB::raw("
                inventory_places.inv_name,
                product_skus.ps_selected_variant_type_values_text,
                " . JsF("products.pro_name", "product_name") . "
            "))
            ->join("products", "products.pro_id", "=", "supplier_order_items.pro_id")
            ->join("product_skus", "product_skus.ps_id", "=", "supplier_order_items.pro_sku_id")
            ->join('inventory_places','inventory_places.inv_id','=','supplier_order_items.inventory_id');
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getOrderItemsByOrderId($orderId)
    {
        $items = self::getData([
            "free_conds" => [
                "supplier_order_id= $orderId",
            ],
            "need_product_join" => true,
        ]);

        return productNameBindingWithVariantValueNameHandler($items);
    }

    public static function getOrderItemsByIds($itemsIds)
    {

        return self::getData([
            "whereIn" => [
                "id" => $itemsIds
            ]
        ]);

    }
}
