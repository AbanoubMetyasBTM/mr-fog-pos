<?php

namespace App\Http\Controllers\admin\reports;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branches_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\client\clients_order_items_m;
use App\models\client\clients_orders_m;
use App\models\product\product_skus_m;
use App\models\product\products_m;
use Illuminate\Http\Request;

class ClientOrdersReportsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    private function appendBranchIdToRequest(Request $request)
    {
        if ($this->branch_id != null) {
            $request["branch_id"] = $this->branch_id;
        }

        return $request;
    }

    #region reports

    #region group-report-sold-products

    private function _returnSoldProductsData(Request $request, $results)
    {
        $this->data['show_input_year']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;
        $this->data["all_brands"]                        = brands_m::getAllBrands();
        $this->data["all_cats"]                          = categories_m::getSubCats();
        $this->data['report_sold_products_buy']          = $results->where('operation_type', '=', 'buy');
        $this->data['report_sold_products_return']       = $results->where('operation_type', '=', 'return');
        $this->data["allBranches"]                       = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["request_data"]                      = (object)$request;
    }

    #region report-Sold-Products

    public function soldProducts(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProducts($request->all());
        $this->_returnSoldProductsData($request, $this->data['report_sold_products']);

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products");

    }

    public function soldProductsYearly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products Yearly");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProductsYearly($request->all());

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->_returnSoldProductsData($request, $this->data['report_sold_products']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products");

    }

    public function soldProductsMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products Monthly");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProductsMonthly($request->all());
        $this->_returnSoldProductsData($request, $this->data['report_sold_products']);

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products");

    }

    #endregion

    #region report-Sold-Products-Sales-Sum

    public function reportSoldProductsSalesSum(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSalesSum'] = clients_order_items_m::reportSoldProductsSalesSum($request->all());
        $this->_returnSoldProductsData($request, $this->data['reportSoldProductsSalesSum']);

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products_sales_sum");

    }

    public function reportSoldProductsSalesSumYearly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products Yearly");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSalesSum'] = clients_order_items_m::reportSoldProductsSalesSumYearly($request->all());

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->_returnSoldProductsData($request, $this->data['reportSoldProductsSalesSum']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products_sales_sum");

    }

    public function reportSoldProductsSalesSumMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products Monthly");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSalesSum'] = clients_order_items_m::reportSoldProductsSalesSumMonthly($request->all());
        $this->_returnSoldProductsData($request, $this->data['reportSoldProductsSalesSum']);

        if(!empty($request->get("product_id"))){
            $this->data["product_obj"] = products_m::getProductById($request->get("product_id"));
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products.sold_products_sales_sum");

    }

    #endregion

    #endregion

    #region group-report-sold-Products-SKU

    private function _returnSoldProductsSKUData(Request $request, $results)
    {
        $this->data['show_input_year']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;
        $this->data["all_brands"]                        = brands_m::getAllBrands();
        $this->data["all_cats"]                          = categories_m::getSubCats();
        $this->data['report_sold_products_buy']          = $results->where('operation_type', '=', 'buy');
        $this->data['report_sold_products_return']       = $results->where('operation_type', '=', 'return');
        $this->data["all_branches"]                      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["request_data"]                      = (object)$request;
    }

    #region report-sold-products-SKU

    public function soldProductsSKU(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products SKUS");
        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProductsSKU($request->all());
        $this->_returnSoldProductsSKUData($request, $this->data['report_sold_products']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku");

    }

    public function soldProductsSKUYearly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products SKUS");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProductsSKUYearly($request->all());
        $this->_returnSoldProductsSKUData($request, $this->data['report_sold_products']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku");

    }

    public function soldProductsSKUMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Products SKUS");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['report_sold_products'] = clients_order_items_m::reportSoldProductsSKUMonthly($request->all());
        $this->_returnSoldProductsSKUData($request, $this->data['report_sold_products']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Monthly';
        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku");

    }

    #endregion

    #region report-sold-products-SKU-sales-sum

    public function soldProductsSKUSalesSum(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products SKUS");
        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSKUSalesSum'] = clients_order_items_m::reportSoldProductsSKUSalesSum($request->all());
        $this->_returnSoldProductsSKUData($request, $this->data['reportSoldProductsSKUSalesSum']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku_sales_sum");

    }

    public function soldProductsSKUSalesSumYearly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products SKUS");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSKUSalesSum'] = clients_order_items_m::reportSoldProductsSKUSalesSumYearly($request->all());

        $this->_returnSoldProductsSKUData($request, $this->data['reportSoldProductsSKUSalesSum']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku_sales_sum");

    }

    public function soldProductsSKUSalesSumMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Products SKUS");

        $request                            = $this->appendBranchIdToRequest($request);
        $this->data['reportSoldProductsSKUSalesSum'] = clients_order_items_m::reportSoldProductsSKUSalesSumMonthly($request->all());
        $this->_returnSoldProductsSKUData($request, $this->data['reportSoldProductsSKUSalesSum']);

        if(!empty($request->get("sku_id"))){
            $this->data["product_sku_obj"] = (product_skus_m::getProductSkusWithVariantValues($request->get("sku_id")))->first();
        }

        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Monthly';
        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.sold_products_sku.sold_products_sku_sales_sum");

    }

    #endregion


    #endregion

    #region clientOrdersCount

    private function _returnClientOrdersCountData(Request $request, $results)
    {
        $this->data["request_data"]                      = (object)$request;
        $this->data['show_input_year']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;

    }

    public function clientOrdersCount(Request $request)
    {

        $this->setMetaTitle("Branches Orders Count");

        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalCountOrdersByBranch($request->all());
        $this->_returnClientOrdersCountData($request, $this->data['total_orders_by_branch']);
        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders.client_orders_count");

    }

    public function clientOrdersCountYearly(Request $request)
    {

        $this->setMetaTitle("Branches Orders Count Yearly");
        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalOrdersByBranchYearly($request->all());
        $this->_returnClientOrdersCountData($request, $this->data['total_orders_by_branch']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders.client_orders_count");

    }

    public function clientOrdersCountMonthly(Request $request)
    {

        $this->setMetaTitle("Branches Orders Count Monthly");
        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalOrdersByBranchMonthly($request->all());
        $this->_returnClientOrdersCountData($request, $this->data['total_orders_by_branch']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders.client_orders_count");

    }

    #endregion

    #region clientOrdersSum

    private function _returnClientOrdersSumData(Request $request, $results)
    {
        $this->data["request_data"]                      = (object)$request;
        $this->data['show_input_year']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;

    }

    public function clientOrdersSumYearly(Request $request)
    {

        $this->setMetaTitle("Branches Orders Sales Sum Yearly");

        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalSumOrdersByBranchYearly($request->all());
        $this->_returnClientOrdersSumData($request, $this->data['total_orders_by_branch']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders_sum.client_orders_sum");

    }

    public function clientOrdersSumMonthly(Request $request)
    {

        $this->setMetaTitle("Branches Orders Sales Sum Monthly");

        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalSumOrdersByBranchMonthly($request->all());
        $this->_returnClientOrdersSumData($request, $this->data['total_orders_by_branch']);
        $this->data['show_input_year']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders_sum.client_orders_sum");

    }

    public function clientOrdersSum(Request $request)
    {

        $this->setMetaTitle("Branches Orders Sales Sum Monthly");

        $this->data['total_orders_by_branch'] = clients_orders_m::getReportTotalSumOrdersByBranch($request->all());
        $this->_returnClientOrdersSumData($request, $this->data['total_orders_by_branch']);
        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_orders_sum.client_orders_sum");

    }

    #endregion

    #region report-group-orders-category

    private function _returnSumClientOrdersItemsByCategoriesData(Request $request, $results)
    {

        $this->data["all_brands"]                        = brands_m::getAllBrands();
        $this->data["all_cats"]                          = categories_m::getSubCats();
        $this->data["all_branches"]                      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data['report_sold_products_buy']          = $results->where('operation_type', '=', 'buy');
        $this->data['report_sold_products_return']       = $results->where('operation_type', '=', 'return');
        $this->data["request_data"]                      = (object)$request;
        $this->data['show_year_input']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;

    }

    #region report-Orders-Quantities-Category

    public function clientOrdersItemsQuantitiesByCategoriesYearly(Request $request)
    {


        $this->setMetaTitle("Sales Count By Categories yearly");

        $request                       = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesQuantitiesYearly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_categories");

    }

    public function clientOrdersItemsQuantitiesByCategoriesMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Categories Monthly");

        $request                           = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesQuantitiesMonthly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_categories");

    }

    public function clientOrdersItemsQuantitiesByCategories(Request $request)
    {

        $this->setMetaTitle("Sales Count By Categories");

        $request                       = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesQuantities($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';


        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_categories");

    }

    #endregion

    #region report-Orders-Sales-Sum-Category

    public function clientOrdersItemsSalesSumByCategoriesYearly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Categories Yearly");

        $request                           = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesSalesSumYearly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_categories");

    }

    public function clientOrdersItemsSalesSumByCategoriesMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Categories Monthly");

        $request                           = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesSalesSumMonthly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_categories");

    }

    public function clientOrdersItemsSalesSumByCategories(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Categories");

        $request                           = $this->appendBranchIdToRequest($request);
        $this->data['orders_category'] = clients_order_items_m::getReportOrdersCategoriesSalesSum($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_category']);

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';


        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_categories");

    }

    #endregion

    #endregion

    #region report-group-orders-Quantities-Brands
    private function _returnSumClientOrdersItemsByBrandsData(Request $request, $results)
    {

        $this->data["all_brands"]                        = brands_m::getAllBrands();
        $this->data["all_cats"]                          = categories_m::getSubCats();
        $this->data["all_branches"]                      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data['report_sold_products_buy']          = $results->where('operation_type', '=', 'buy');
        $this->data['report_sold_products_return']       = $results->where('operation_type', '=', 'return');
        $this->data["request_data"]                      = (object)$request;
        $this->data['show_year_input']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;

    }

    #region OrdersItemsBrands

    public function clientOrdersItemsQuantitiesByBrandsYearly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Brands Yearly");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['sum_orders_brands'] = clients_order_items_m::getReportSUMOrdersBrandsYearly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['sum_orders_brands']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_brands");

    }

    public function clientOrdersItemsQuantitiesByBrandsMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Count By Brands Monthly");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['sum_orders_brands'] = clients_order_items_m::getReportSUMOrdersBrandsMonthly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['sum_orders_brands']);


        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_brands");

    }

    public function clientOrdersItemsQuantitiesByBrands(Request $request)
    {

        $this->setMetaTitle("Sales Count By Brands");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['sum_orders_brands'] = clients_order_items_m::getReportSUMOrdersBrands($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['sum_orders_brands']);

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';


        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_by_brands");

    }

    #endregion

    #region OrdersItemsBrandsSalesSum

    public function clientOrdersItemsSalesSumByBrandsYearly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Brands Yearly");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['orders_brands'] = clients_order_items_m::getReportOrdersBrandsSalesSumYearly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_brands']);

        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Yearly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_brands");

    }

    public function clientOrdersItemsSalesSumByBrandsMonthly(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Brands monthly");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['orders_brands'] = clients_order_items_m::getReportOrdersBrandsSalesSumMonthly($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_brands']);


        $this->data['show_year_input']  = true;
        $this->data['report_type_date'] = 'Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_brands");

    }

    public function clientOrdersItemsSalesSumByBrands(Request $request)
    {

        $this->setMetaTitle("Sales Sum By Brands");

        $request                         = $this->appendBranchIdToRequest($request);
        $this->data['orders_brands'] = clients_order_items_m::getReportOrdersBrandsSalesSum($request->all());
        $this->_returnSumClientOrdersItemsByCategoriesData($request, $this->data['orders_brands']);

        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date']                  = 'From,To Date';


        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.orders.client_orders_sales_sum_by_brands");

    }

    #endregion

    #endregion

    #endregion


}
