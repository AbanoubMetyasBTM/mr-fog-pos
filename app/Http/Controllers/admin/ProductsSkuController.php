<?php

namespace App\Http\Controllers\admin;

use App\form_builder\ProductsBuilder;
use App\form_builder\ProductsSkuBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\inventory\inventories_products_m;
use App\models\product\product_skus_m;
use App\models\product\product_variant_type_values_m;
use App\models\product\product_variant_types_m;
use App\models\product\products_m;
use Illuminate\Http\Request;

class ProductsSkuController extends AdminBaseController
{

    use CrudTrait;

    /** @var product_skus_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Products");

        $this->modelClass          = product_skus_m::class;
        $this->viewSegment         = "products_sku";
        $this->routeSegment        = "products-sku";
        $this->builderObj          = new ProductsSkuBuilder();
        $this->primaryKey          = "ps_id";
        $this->enableAutoTranslate = true;
        $this->editOnly            = true;

    }

    private function createProductSkus(int $proId)
    {

        $oldSkus           = product_skus_m::getProductSkus($proId);
        $proVariants       = product_variant_types_m::getProductVariantTypes($proId);
        $proVariantsValues = product_variant_type_values_m::getProductVariantValues($proId);

        $variantCombinations = null;
        foreach ($proVariants as $variant) {
            if ($variantCombinations == null) {
                $variantCombinations = collect($proVariantsValues->where("variant_type_id", $variant->variant_type_id)->pluck("vt_value_id")->all());
                continue;
            }

            $variantCombinations = $variantCombinations->crossJoin($proVariantsValues->where("variant_type_id", $variant->variant_type_id)->pluck("vt_value_id")->all());
        }

        if ($variantCombinations == null) {
            $variantCombinations = collect([0]);
        }
        $variantCombinations = $variantCombinations->all();


        $newSkus = [];
        foreach ($variantCombinations as $combination) {

            $selectedVariantTypeValues = collect($combination)->flatten(0)->all();
            sort($selectedVariantTypeValues);
            $selectedVariantTypeValues = implode(",", $selectedVariantTypeValues);

            if (is_object($oldSkus->where("ps_selected_variant_type_values", $selectedVariantTypeValues)->first())) {
                continue;
            }

            $newSkus[] = [
                'pro_id'                          => $proId,
                'ps_selected_variant_type_values' => $selectedVariantTypeValues,
                'use_default_images'              => 1,
                'is_active'                       => 1,
                'created_at'                      => date("Y-m-d H:i:s"),
                'updated_at'                      => date("Y-m-d H:i:s"),
            ];

        }

        if (count($newSkus) > 0) {
            product_skus_m::insert($newSkus);
        }

    }

    private function getSkuName($product_obj, $item, array $pro_variants_values)
    {

        if ($item->ps_selected_variant_type_values == "0") {
            return $product_obj->pro_name;
        }

        $itemName = "";

        $ps_selected_variant_type_values = explode(",", $item->ps_selected_variant_type_values);
        foreach ($ps_selected_variant_type_values as $vkey => $value) {

            if ($vkey > 0) {
                $itemName .= "-";
            }

            if (isset($pro_variants_values[$value])) {
                $itemName .= ($pro_variants_values[$value][0]->vt_value_name);
            }
            else {
                $itemName .= $value;
            }
        }

        return $itemName;

    }

    private function reNameSkuSelectedVariantTypeValuesText($product_obj, $pro_variants_values)
    {

        $product_skus = product_skus_m::getProductSkus($product_obj->pro_id);

        foreach ($product_skus as $key => $item) {
            $item->update([
                "ps_selected_variant_type_values_text" => $this->getSkuName($product_obj, $item, $pro_variants_values)
            ]);
        }

    }

    public function showProductSkus(Request $request, int $proId, int $proSkuId = null)
    {

        havePermissionOrRedirect("admin/products", "show_product_skus");

        $this->data["product_obj"]         = products_m::getProductById($proId);
        $this->data["pro_variants_values"] = product_variant_type_values_m::getProductVariantValues($proId)->groupBy("vt_value_id")->all();

        $this->createProductSkus($proId);
        $this->reNameSkuSelectedVariantTypeValuesText($this->data["product_obj"], $this->data["pro_variants_values"]);

        $this->data["product_skus"] = product_skus_m::getProductSkus($proId);
        $this->data["pro_sku_id"]   = $proSkuId;

        return $this->returnView($request, "admin.subviews.products_sku.show_skus");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/products", "show_product_skus");

        $productSkuObj             = product_skus_m::findOrFail($item_id);
        $this->data["product_obj"] = products_m::getProductById($productSkuObj->pro_id);

    }

    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {

        return [
            "msg"      => "Saved Successfully",
            "redirect" => url("admin/products-sku/show/$item_obj->pro_id"),
        ];

    }


    public function editProductSku(Request $request)
    {

        $field_name = $request['field_name'];
        $value      = $request->get("value");

        $checkDuplicated = product_skus_m::checkBarcodeIsDuplicated($value, $field_name, $request['item_id']);
        if ($checkDuplicated) {
            return json_encode([
                "error" => "please user another barcode"
            ]);
        }

        echo $this->general_self_edit($request);

    }


    public function getProductSkusByBarcode(Request $request)
    {
        $products  = product_skus_m::getProductByNameOrBarcode($request->get("q"));

        $result = [];
        $result["totalRecords"] = count($products);

        if (!empty($request->get("add_all"))) {
            $result["results"][] = [
                'id'           => "",
                'display_text' => "all",
            ];
        }

        foreach ($products as $key => $product) {

            $result["results"][] = [
                'id'              => $product['ps_id'],
                'display_text'    => $product['pro_name'],
                "search_q_is_box" => $request->get("q") == $product["ps_box_barcode"] ? true : false,
            ];

        }

        return json_encode($result);

    }




}
