<?php

namespace App\Http\Controllers\admin\reports;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\models\branch\branch_inventory_m;
use App\models\branch\branches_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\client\clients_m;
use App\models\inventory\inventories_m;
use App\models\inventory\inventories_products_m;
use Illuminate\Http\Request;

class InventoryReportsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    private function _prepareQuantitiesAtInventoriesData(Request $request, $results)
    {
        $requestData                = $request->all();
        $this->data["request_data"] = (object)$requestData;
        $this->data["all_inventories"]    = branch_inventory_m::getSearchInventoriesData();
        $this->data["all_brands"]         = brands_m::getAllBrands();
        $this->data["all_cats"]           = categories_m::getSubCats();
        $this->data['all_branches']       = branches_m::getAllBranchesOrCurrentBranchOnly();

    }


    public function getInventoryQuantitiesGroupedByCategory(Request $request)
    {

        $this->setMetaTitle("Get Inventory Quantities Grouped By Category");

        $this->data['total_inventory_quantities']= inventories_products_m::reportGetInventoryQuantitiesGroupedByCategory();
        $this->data['type']= 'Group By Category';

        return $this->returnView($request, "admin.subviews.reports.inventory.total_quantities_at_Inventories.total_quantities_at_inventories_grouped_by_category_or_brand");

    }

    public function getInventoryQuantitiesGroupedByBrand(Request $request)
    {

        $this->setMetaTitle("Get Inventory Quantities Grouped By Category");

        $this->data['total_inventory_quantities']= inventories_products_m::reportGetInventoryQuantitiesGroupedByBrand();
        $this->data['type']= 'Group By Brand';

        return $this->returnView($request, "admin.subviews.reports.inventory.total_quantities_at_Inventories.total_quantities_at_inventories_grouped_by_category_or_brand");

    }



}
