<?php

namespace App\Http\Controllers\admin;

use App\form_builder\ProductPromotionsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\product\product_promotions_m;
use App\models\product\product_skus_m;
use Illuminate\Http\Request;

class ProductPromotionsController extends AdminBaseController
{

    use CrudTrait;

    /** @var product_promotions_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Product Promotions");

        $this->modelClass          = product_promotions_m::class;
        $this->viewSegment         = "product_promotions";
        $this->routeSegment        = "product-promotions";
        $this->builderObj          = new ProductPromotionsBuilder();
        $this->primaryKey          = "promo_id";
        $this->enableAutoTranslate = true;

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/product_promotions", "show_action");

        $conds = $request->all();
        $promotions = $this->modelClass::getAllProductsPromotions($conds);


        $productsSkusIds = [];
        foreach ($promotions as $promotion){
            $promotion->promo_start_at = date("Y-m-d h:i:s", strtotime($promotion->promo_start_at));
            $promotion->promo_end_at   = date("Y-m-d h:i:s", strtotime($promotion->promo_end_at));

            $productsSkusIds = array_merge($productsSkusIds, explode(',', $promotion->promo_sku_ids));

        }

        if (!empty($productsSkusIds)) {

            $productsSkusIds = collect($productsSkusIds)->unique();
            $productsObjs    = collect(product_skus_m::getProductsSkusWithVariantValuesByIds($productsSkusIds));

            foreach ($promotions as $promotion) {
                $promotion->products_names = collect($productsObjs
                    ->whereIn(
                        'ps_id',
                        explode(',', $promotion->promo_sku_ids)
                    ))
                    ->pluck('product_name')->toArray();
            }
        }

        $this->data["results"] = $promotions;
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/product_promotions", $item_id == null ? "add_action" : "edit_action");
    }


    public function beforeSaveRow(Request $request)
    {
        if ($request->get('promo_branch_id') == "0"){
            $request->merge(['promo_branch_id' => NULL]);
        }

        if(
            !is_array($request->get('promo_sku_ids')) ||
            in_array(0, $request->get('promo_sku_ids'))
        ){
            unset($request["promo_sku_ids"]);
        }

        return $request;
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/coupons", "delete_action");
    }



}
