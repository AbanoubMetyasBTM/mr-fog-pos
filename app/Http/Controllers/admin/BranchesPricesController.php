<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branch_prices_m;
use App\models\branch\branches_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\product\product_skus_m;
use App\models\product\products_m;
use Illuminate\Http\Request;

class BranchesPricesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Branch Prices");
    }

    private function populateBTMSelect2Fields(Request $request){

        if (!empty($request->get("pro_id"))) {
            $productObj = products_m::getProductById($request->get("pro_id"));
            if (is_object($productObj)) {
                $this->data["selected_product_name"] = $productObj->pro_name;
            }
        }

        if (!empty($request->get("sku_id"))) {
            $itemObj = product_skus_m::getProductSkusWithVariantValues($request->get("sku_id"))->first();
            if (is_object($itemObj)) {
                $this->data["selected_sku_name"] = $itemObj->product_name." - ".$itemObj->ps_selected_variant_type_values_text;
            }
        }

    }

    public function showBranchPrices(Request $request, $branch_id)
    {

        havePermissionOrRedirect("admin/branches_prices", "show_branch_prices");

        if ($this->branch_id != null && $branch_id != $this->branch_id) {
            return abort(404);
        }

        $this->data["branch"]  = branches_m::getBranchById($branch_id);

        if (!is_object($this->data["branch"])){
            return $this->returnMsgWithRedirection($request, 'admin/branches','branch id not valid');
        }

        $this->checkIfBranchHasAllPricesOfProducts($this->data["branch"]);

        $this->data["request_data"] = (object)$request->all();

        $this->populateBTMSelect2Fields($request);

        $conds                      = $request->all();
        $conds['paginate']          = 50;
        $this->data["all_brands"]   = brands_m::getAllBrands();
        $this->data["all_cats"]     = categories_m::getSubCats();

        $this->data["results"]      = branch_prices_m::getBranchPrices($branch_id, $conds);

        return $this->returnView($request, "admin.subviews.branches_prices.show_branch_prices");

    }

    private function checkIfBranchHasAllPricesOfProducts($branchObj)
    {
        // compare ids (branch_prices, pro_sku)
        //diff data => create

        $branch_id         = $branchObj->branch_id;
        $branch_prices     = branch_prices_m::getBranchPrices($branch_id);
        $products_skus     = product_skus_m::getOnlyActiveSkus();
        $products_skus_ids = collect($products_skus->pluck('ps_id'))->toArray();
        $branch_prices_ids = collect($branch_prices->pluck('sku_id'))->toArray();
        $diff_ids          = array_diff($products_skus_ids, $branch_prices_ids);


        if (!empty($diff_ids)){

            $products_skus_diff_data = [];
            foreach ($diff_ids as  $id){
                $products_skus_diff_data[] = collect($products_skus->where('ps_id', $id)->first())->toArray();
            }

            $data = [];
            foreach($products_skus_diff_data as $key => $item){
                $data[$key]['branch_id']             = $branch_id;
                $data[$key]['pro_id']                = $item['pro_id'];
                $data[$key]['sku_id']                = $item['ps_id'];

                $data[$key]['online_item_price']     = floatval($item['ps_item_retailer_price']) ;
                $data[$key]['online_box_price']      = floatval($item['ps_box_retailer_price']) ;
                $data[$key]['item_retailer_price']   = floatval($item['ps_item_retailer_price']);
                $data[$key]['item_wholesaler_price'] = floatval($item['ps_item_wholesaler_price']);
                $data[$key]['box_retailer_price']    = floatval($item['ps_box_retailer_price']);
                $data[$key]['box_wholesaler_price']  = floatval($item['ps_box_wholesaler_price']);

                $data[$key]['is_active']             = 1;
                $data[$key]['created_at']            = now();
                $data[$key]['updated_at']            = now();
            }

            branch_prices_m::createBranchPrices($data);
        }

    }

    public function updateBranchPrices(Request $request)
    {

        havePermissionOrRedirect("admin/branches_prices", "update_branch_prices");

        $itemObj = branch_prices_m::findOrFail($request->get("item_id"));

        if ($this->branch_id != null && $itemObj->branch_id != $this->branch_id) {
            return abort(404);
        }

        return $this->general_self_edit($request);

    }


    public function showProductBranchesPrices(Request $request, int $productId)
    {

        havePermissionOrRedirect("admin/branches_prices", "show_branch_prices");

        $this->data["product"] = products_m::getProductById($productId);
        $this->data["results"] = branch_prices_m::getBranchesPricesByProductId($productId);

        return $this->returnView($request, "admin.subviews.branches_prices.show_product_branches_prices");

    }
}
