<?php

namespace App\models\client;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class clients_order_items_m extends \Eloquent
{

    use SoftDeletes, ClientOrderItemsReportTrait;

    protected $table = "client_order_items";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'operation_type', 'client_order_id', 'pro_id', 'pro_sku_id',
        'item_type', 'order_quantity', 'item_cost', 'total_items_cost'
    ];


    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            client_order_items.*
        "));


        if(isset($attrs["need_product_join"])){
            $results = $results->addSelect(\DB::raw("
                product_skus.ps_selected_variant_type_values_text,
                " . JsF("products.pro_name", "product_name") . "
            "))
                ->join("products", "products.pro_id", "=", "client_order_items.pro_id")
                ->join("product_skus", "product_skus.ps_id", "=", "client_order_items.pro_sku_id");
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function createOrderItems($items, $orderId)
    {
        foreach ($items as $key => $item){
            $items[$key]['client_order_id'] = $orderId;
        }
        self::insert($items);
    }

    public static function getOrderItemsByOrderId($orderId)
    {
        $items = self::getData([
            "free_conds" => [
                "client_order_id= $orderId",
            ],
            "need_product_join" => true,
        ]);

        return productNameBindingWithVariantValueNameHandler($items);
    }

    public static function updateItemPrice($id, $newPrice, $quantity)
    {

        self::where("id", $id)->update([
            'item_cost'        => $newPrice,
            'total_items_cost' => $newPrice * $quantity
        ]);

    }




}
