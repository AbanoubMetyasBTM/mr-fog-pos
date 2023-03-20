<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\AddOrderHelper;
use App\form_builder\ProductsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branch_inventory_m;
use App\models\branch\branches_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\client\clients_m;
use App\models\inventory\inventories_products_m;
use App\models\product\product_promotions_m;
use App\models\product\product_skus_m;
use App\models\product\product_variant_type_values_m;
use App\models\product\product_variant_types_m;
use App\models\product\products_m;
use Illuminate\Http\Request;

class ProductsController extends AdminBaseController
{

    use CrudTrait;

    /** @var products_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Products");

        $this->modelClass          = products_m::class;
        $this->viewSegment         = "products";
        $this->routeSegment        = "products";
        $this->builderObj          = new ProductsBuilder();
        $this->primaryKey          = "pro_id";
        $this->enableAutoTranslate = true;
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/products", "show_action");

        $this->data["request_data"] = (object)$request->all();
        $conds                      = $request->all();
        $conds['paginate']          = 50;
        $this->data["all_brands"]   = brands_m::getAllBrands();
        $this->data["all_cats"]     = categories_m::getSubCats();
        $this->data["results"]      = $this->modelClass::getAllProducts($conds);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }


    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/products", $item_id == null ? "add_action" : "edit_action");

        if ($item_id != null) {
            $this->data["old_variants"]        = product_variant_types_m::getProductVariantTypes($item_id);
            $this->data["old_variants_values"] = product_variant_type_values_m::getProductVariantValues($item_id);
        }


    }

    private function saveNewVariantNames(Request $request, $proId)
    {

        $oldVariants      = product_variant_types_m::getProductVariantTypes($proId);
        $oldVariantsNames = $oldVariants->pluck("variant_type_name")->all();
        $newVariantsNames = [];

        $newVariantNames = $request->get("new_variant_name");
        foreach ($newVariantNames as $key => $variantName) {

            if (
                empty($variantName) ||
                in_array($variantName, $oldVariantsNames) ||
                in_array($variantName, $newVariantsNames)
            ) {
                continue;
            }


            $variantValues     = $request->get("new_variant_values_{$key}");
            $variantValues     = array_diff($variantValues, [""]);
            $variantValues     = array_unique($variantValues);
            $variantValuesRows = [];

            if (count($variantValues) == 0) {
                continue;
            }

            $variantTypeObj = product_variant_types_m::create([
                "pro_id"            => $proId,
                "variant_type_name" => $variantName,
            ]);

            $newVariants[] = $variantName;

            foreach ($variantValues as $variantValue) {
                $variantValuesRows[] = [
                    'pro_id'          => $proId,
                    'variant_type_id' => $variantTypeObj->variant_type_id,
                    'vt_value_name'   => $variantValue
                ];
            }

            product_variant_type_values_m::insert($variantValuesRows);

        }


    }

    private function editOldVariantNames(Request $request, $proId)
    {


        $oldVariants       = product_variant_types_m::getProductVariantTypes($proId);
        $oldVariantsValues = product_variant_type_values_m::getProductVariantValues($proId);

        foreach ($oldVariants as $key => $variant) {

            $typeValues = $request->get("new_variant_values_for_old_items_{$variant->variant_type_id}");
            if (empty($typeValues)) {
                $typeValues = [];
            }

            $oldTypeValues = $oldVariantsValues->where("variant_type_id", $variant->variant_type_id)->pluck("vt_value_name")->all();

            $typeValues = array_diff($typeValues, [""]);
            $typeValues = array_diff($typeValues, $oldTypeValues);

            if (count($typeValues) == 0) {
                continue;
            }
            $variantValuesRows = [];

            foreach ($typeValues as $variantValue) {
                $variantValuesRows[] = [
                    'pro_id'          => $proId,
                    'variant_type_id' => $variant->variant_type_id,
                    'vt_value_name'   => $variantValue
                ];
            }

            product_variant_type_values_m::insert($variantValuesRows);

        }


    }

    public function afterSave(Request $request, $item_obj)
    {

        $this->editOldVariantNames($request, $item_obj->pro_id);
        $this->saveNewVariantNames($request, $item_obj->pro_id);

    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/products", "delete_action");

        $checkExist = inventories_products_m::checkIfProductExistAtAnyInventory($request->get("item_id"));
        if ($checkExist) {
            return json_encode([
                "msg" => "you can not delete this product, because it has log at inventory"
            ]);
        }

        $this->general_remove_item($request, $this->modelClass);

    }

    public function deleteVariantType(Request $request)
    {

        havePermissionOrRedirect("admin/products", "delete_variant");

        $variantTypeObj = product_variant_types_m::findOrFail($request->get("item_id"));

        $checkExist = inventories_products_m::checkIfProductExistAtAnyInventory($variantTypeObj->pro_id);
        if ($checkExist) {
            return json_encode([
                "msg" => "you can not delete this, because it has log at inventory"
            ]);
        }

        $this->general_remove_item($request, product_variant_types_m::class);

    }

    public function deleteVariantTypeValue(Request $request)
    {

        havePermissionOrRedirect("admin/products", "delete_variant");

        $variantTypeValueObj = product_variant_type_values_m::findOrFail($request->get("item_id"));

        $checkExist = inventories_products_m::checkIfProductExistAtAnyInventory($variantTypeValueObj->pro_id);
        if ($checkExist) {
            return json_encode([
                "msg" => "you can not delete this, because it has log at inventory"
            ]);
        }

        $this->general_remove_item($request, product_variant_type_values_m::class);

    }

    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {

        return [
            "msg"      => "Saved Successfully",
            "redirect" => url("admin/products-sku/show/$item_obj->pro_id"),
        ];

    }

    #region search-product

    private function getProductPromotion($allPromotions, $skuId){

        foreach ($allPromotions as $promotion){
            if (empty($promotion->promo_sku_ids)){
                return $promotion;
            }

            if(strpos($promotion->promo_sku_ids,'"'.$skuId.'"') > 0){
                return $promotion;
            }
        }

    }

    private function applyPromotionAtProduct($promotion, $productAvailableInBranch)
    {

        if(!is_object($promotion)){
            return $productAvailableInBranch;
        }

        $priceArr = [
            "item_retailer_price",
            "item_wholesaler_price",
            "box_retailer_price",
            "box_wholesaler_price",
        ];

        $productAvailableInBranch->promotionText = "Promotion applied at product by percentage $promotion->promo_discount_percent";

        foreach ($priceArr as $item){
            $productAvailableInBranch->{$item}   = floatval($productAvailableInBranch->{$item});
            $productAvailableInBranch->{$item}   = $productAvailableInBranch->{$item} - ($productAvailableInBranch->{$item} * (floatval($promotion->promo_discount_percent) / 100));
        }

        return $productAvailableInBranch;

    }

    private function applyTaxes($productAvailableInBranch, $taxes)
    {

        if(!is_array($taxes)){
            return $productAvailableInBranch;
        }

        $priceArr = [
            "item_retailer_price",
            "item_wholesaler_price",
            "box_retailer_price",
            "box_wholesaler_price",
        ];

        $taxesTotalValue = collect($taxes)->pluck("tax_percent")->all();
        $taxesTotalValue = array_sum($taxesTotalValue);

        foreach ($priceArr as $item) {

            $taxValue = floatval($productAvailableInBranch->{$item}) * ($taxesTotalValue / 100);
            $taxValue = round($taxValue, 2);

            $productAvailableInBranch->{$item} = floatval($productAvailableInBranch->{$item}) + floatval($taxValue);
        }



        return $productAvailableInBranch;

    }

    public function getProductSkusByNameOrBarcodeOfOrderClient(Request $request)
    {

        $branchId        = $request->session()->get('current_branch_id');
        $mainInvOfBranch = branch_inventory_m::getMainInvOfBranch($branchId);

        $branchObj       = branches_m::getBranchById($branchId);
        $applyTaxes      = json_decode($branchObj->branch_taxes);

        $clientId        = $request->get("selected_client_id");
        $clientObj       = clients_m::getClientDataById($clientId);
        if(!empty($clientObj->group_taxes)){
            $applyTaxes = json_decode($clientObj->group_taxes);
        }


        $productsAvailableInBranch = collect(product_skus_m::getProductInBranchByNameOrBarcode($branchId, $request->get("q")));
        $productsSkusIdsAvailableInBranch = collect($productsAvailableInBranch)->pluck('ps_id');

        $productsAvailableInMainInvOfBranch = inventories_products_m::getInventoryProductByInvIdAndProductsSkuIds(
            $mainInvOfBranch->inventory_id,
            $productsSkusIdsAvailableInBranch
        );
        $productsAvailableInMainInvOfBranch = collect($productsAvailableInMainInvOfBranch);
        $result["totalRecords"]             = count($productsAvailableInMainInvOfBranch);

        $allPromotions = product_promotions_m::getPromotionForSpecificBranchAndAllBranches($branchId);


        foreach($productsAvailableInMainInvOfBranch as $key => $product){

            $productAvailableInBranch = $productsAvailableInBranch->where('ps_id', '=', $product->pro_sku_id)->first();
            $promotion                = $this->getProductPromotion($allPromotions, $product->pro_sku_id);

            $productAvailableInBranch = $this->applyTaxes($productAvailableInBranch, $applyTaxes);
            $productAvailableInBranch = $this->applyPromotionAtProduct($promotion, $productAvailableInBranch);

            $result["results"][$key]['id']                    = $product->pro_sku_id;
            $result["results"][$key]['ps_box_barcode']        = $productAvailableInBranch->ps_box_barcode;
            $result["results"][$key]['ps_item_barcode']       = $productAvailableInBranch->ps_item_barcode;
            $result["results"][$key]['item_retailer_price']   = $productAvailableInBranch->item_retailer_price;
            $result["results"][$key]['item_wholesaler_price'] = $productAvailableInBranch->item_wholesaler_price;
            $result["results"][$key]['box_retailer_price']    = $productAvailableInBranch->box_retailer_price;
            $result["results"][$key]['box_wholesaler_price']  = $productAvailableInBranch->box_wholesaler_price;
            $result["results"][$key]['display_text']          = $productAvailableInBranch->pro_name;
            $result["results"][$key]['ip_box_quantity']       = $product['ip_box_quantity'];
            $result["results"][$key]['ip_item_quantity']      = $product['ip_item_quantity'];
            $result["results"][$key]['total_items_quantity']  = $product['total_items_quantity'];
            $result["results"][$key]['promotion_text']        = $productAvailableInBranch->promotionText ?? "";
            $result["results"][$key]['promotion']             = $promotion;


            if ($request->get("q") == $productAvailableInBranch->ps_box_barcode){
                $result["results"][$key]['search_q_is_box'] = true;
            }
            else{
                $result["results"][$key]['search_q_is_box'] = false;
            }

        }

        return json_encode($result);
    }

    public function getProductByName(Request $request)
    {
        $products               = products_m::getProductByName($request->get("q"));
        $result["totalRecords"] = count($products);

        if (!empty($request->get("add_all"))) {
            $result["results"][] = [
                'id'           => "",
                'display_text' => "all",
            ];
        }

        foreach ($products as $key => $product) {
            $result["results"][] = [
                'id'           => $product->pro_id,
                'display_text' => $product->pro_name,
            ];
        }

        return json_encode($result);
    }

    #endregion


}
