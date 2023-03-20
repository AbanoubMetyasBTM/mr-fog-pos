<?php


namespace App\models\client;


use Illuminate\Support\Facades\DB;

trait ClientOrderItemsReportTrait
{



    #region group-report-Sold-Products

    private static function _reportPrepareSoldProductsConditions($result, $attrs)
    {

        $attrs["branch_id"]     = Vsi($attrs["branch_id"] ?? "");
        $attrs['selected_year'] = Vsi($attrs['selected_year'] ?? "");
        $attrs['product_id']    = Vsi($attrs['product_id'] ?? "");
        $attrs['date_from']     = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']       = Vsi($attrs['date_to'] ?? "");
        $attrs['cat_id']        = Vsi($attrs['cat_id'] ?? "");
        $attrs['brand_id']      = Vsi($attrs['brand_id'] ?? "");



        if (!empty($attrs["branch_id"]) && $attrs["branch_id"] != 'all') {
            $result = $result->
            join('client_orders','client_orders.client_order_id','client_order_items.client_order_id')->
            where('client_orders.branch_id','=', $attrs["branch_id"]);
        }

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(client_order_items.created_at) = '".$attrs['selected_year']."'"));
        }

        if (!empty($attrs['product_id'])) {
            $result = $result->
            where('client_order_items.pro_id', '=', $attrs['product_id']);
        }

        if (!empty($attrs['cat_id']) && $attrs['cat_id'] !='all'){
            $result = $result->
            where('products.cat_id', '=' , $attrs['cat_id']);
        }

        if (!empty($attrs['brand_id']) && $attrs['brand_id'] !='all'){
            $result = $result->
            where('products.brand_id', '=' , $attrs['brand_id']);
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])){

            $attrs['date_from']     = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']       = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(client_order_items.created_at)'), [$attrs['date_from'],$attrs['date_to']]);
        }

        return $result;

    }

    #region report-Sold-Products

    public static function reportSoldProducts($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
            concat (
                ".JsFT("products.pro_name")."
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','=','client_order_items.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts = $reportSoldProducts->
        orderBy(\DB::raw("SUM(client_order_items.order_quantity)"), 'desc')->
        groupBy(\DB::raw("
            client_order_items.pro_id,
            client_order_items.operation_type
        "))->get();


    }

    public static function reportSoldProductsMonthly($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
              concat (
                ".JsFT("products.pro_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return  $reportSoldProducts->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'), 'desc')->
        groupBy(\DB::raw("
                client_order_items.pro_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function reportSoldProductsYearly($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
            Concat (
                ".JsFT("products.pro_name").",
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                client_order_items.pro_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }

    #endregion

    #region report-Sold-Products-Sales-Sum

    public static function reportSoldProductsSalesSum($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
            concat (
                branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                branches.branch_currency
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','=','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts = $reportSoldProducts->
        orderBy(\DB::raw("SUM(client_order_items.total_items_cost)"), 'desc')->
        groupBy(\DB::raw("
            client_order_items.pro_id,
            branches.branch_id,
            client_order_items.operation_type
        "))->get();


    }

    public static function reportSoldProductsSalesSumMonthly($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
              concat (
               branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                branches.branch_currency,
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return  $reportSoldProducts->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'), 'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                client_order_items.pro_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function reportSoldProductsSalesSumYearly($attrs)
    {

        $reportSoldProducts= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                branches.branch_currency,
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                client_order_items.pro_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }


    #endregion


    #endregion

    #region group-report-Sold-Products-sku

    private static function _reportPrepareSoldProductsSKUConditions($result, $attrs)
    {
        $attrs["branch_id"]     = Vsi($attrs["branch_id"] ?? "");
        $attrs['selected_year'] = Vsi($attrs['selected_year'] ?? "");
        $attrs['sku_id']        = Vsi($attrs['sku_id'] ?? "");
        $attrs['date_from']     = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']       = Vsi($attrs['date_to'] ?? "");
        $attrs['cat_id']        = Vsi($attrs['cat_id'] ?? "");
        $attrs['brand_id']      = Vsi($attrs['brand_id'] ?? "");

        if ($attrs["branch_id"] != 'all' && !empty($attrs["branch_id"])) {
            $result = $result->
            join('client_orders','client_orders.client_order_id','client_order_items.client_order_id')->
            where('client_orders.branch_id','=', $attrs["branch_id"]);
        }

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(client_order_items.created_at) = '".$attrs['selected_year']."'"));
        }

        if (!empty($attrs['sku_id'])) {
            $result = $result->
            where('client_order_items.pro_sku_id', '=', $attrs['sku_id']);
        }

        if (!empty($attrs['cat_id']) && $attrs['cat_id'] !='all'){
            $result = $result->
            where('products.cat_id', '=' , $attrs['cat_id']);
        }

        if (!empty($attrs['brand_id']) && $attrs['brand_id'] !='all'){
            $result = $result->
            where('products.brand_id', '=' , $attrs['brand_id']);
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])){

            $attrs['date_from']     = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']       = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(client_order_items.created_at)'), [$attrs['date_from'],$attrs['date_to']]);
        }

        return $result;

    }

    #region report-Sold-Products-sku

    public static function reportSoldProductsSKU($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','product_skus.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(' SUM(client_order_items.order_quantity)'),'desc')->
        groupBy('client_order_items.pro_sku_id','client_order_items.operation_type')->
        get();

    }

    public static function reportSoldProductsSKUYearly($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text,
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','product_skus.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(' SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                client_order_items.pro_sku_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->
        get();

    }

    public static function reportSoldProductsSKUMonthly($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text,
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','product_skus.pro_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(' SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                client_order_items.pro_sku_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->
        get();

    }

    #endregion

    #region report-Sold-Products-sku-sales-sum

    public static function reportSoldProductsSKUSalesSum($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
                branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text,
                ' - ',
                branches.branch_currency
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            client_order_items.operation_type,
            branches.branch_currency,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(
            ' SUM(client_order_items.total_items_cost)'),'desc'
        )->
        groupBy(
            'client_order_items.pro_sku_id',
            'branches.branch_id',
            'client_order_items.operation_type'
        )->
        get();

    }

    public static function reportSoldProductsSKUSalesSumYearly($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
               branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text,
                ' - ',
                branches.branch_currency,
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            client_order_items.operation_type,
            branches.branch_currency,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(' SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                client_order_items.pro_sku_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->
        get();

    }

    public static function reportSoldProductsSKUSalesSumMonthly($attrs)
    {

        $reportSoldProducts = self::
        select(\DB::raw("
            concat (
                branches.branch_name,
                ' - ',
                ".JsFT("products.pro_name").",
                ' - ',
                product_skus.ps_selected_variant_type_values_text,
                ' - ',
                branches.branch_currency,
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            client_order_items.operation_type,
            branches.branch_currency,
            client_order_items.pro_sku_id
        "))->
        join('product_skus','product_skus.ps_id','client_order_items.pro_sku_id')->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSoldProducts = self::_reportPrepareSoldProductsSKUConditions($reportSoldProducts, $attrs);

        return $reportSoldProducts->orderBy(\DB::raw(
            'SUM(client_order_items.total_items_cost)')
            ,'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                client_order_items.pro_sku_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->
        get();

    }

    #endregion

    #endregion

    #region group-report-orders-categories

    public static function getReportOrdersCategoriesQuantitiesYearly($attrs)
    {
        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }

    public static function getReportOrdersCategoriesQuantitiesMonthly($attrs)
    {

        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function getReportOrdersCategoriesQuantities($attrs)
    {

        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    #endregion

    #region group-report-orders-categories-Sales-Sum

    public static function getReportOrdersCategoriesSalesSumYearly($attrs)
    {
        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }

    public static function getReportOrdersCategoriesSalesSumMonthly($attrs)
    {

        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function getReportOrdersCategoriesSalesSum($attrs)
    {

        $reportSUMOrdersCategories= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("categories.cat_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('categories','categories.cat_id','products.cat_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSUMOrdersCategories = self::_reportPrepareSoldProductsConditions($reportSUMOrdersCategories, $attrs);

        return $reportSUMOrdersCategories->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.cat_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    #endregion

    #region report-orders-brands-quantities

    public static function getReportSUMOrdersBrandsYearly($attrs)
    {
        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id');

        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }


    public static function getReportSUMOrdersBrandsMonthly($attrs)
    {

        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id');

        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function getReportSUMOrdersBrands($attrs)
    {

        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.order_quantity) as 'order_quantity_sum',
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id');

        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.order_quantity)'),'desc')->
        groupBy(\DB::raw("
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    #endregion

    #region report-orders-brands-Sales-Sum

    public static function getReportOrdersBrandsSalesSumYearly($attrs)
    {
        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_id,
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');


        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at)
            "))->get();

    }


    public static function getReportOrdersBrandsSalesSumMonthly($attrs)
    {

        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_id,
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    public static function getReportOrdersBrandsSalesSum($attrs)
    {

        $reportSUMOrdersBrands= self::
        select(\DB::raw("
            Concat (
                branches.branch_name,
                ' - ',
                ".JsFT("brands.brand_name").",
                ' - ',
                YEAR(client_order_items.created_at),
                ' - ',
                MONTH(client_order_items.created_at)
            ) as 'item_name',
            SUM(client_order_items.total_items_cost) as 'total_items_cost',
            branches.branch_id,
            branches.branch_currency,
            client_order_items.operation_type
        "))->
        join('products','products.pro_id','client_order_items.pro_id')->
        join('brands','brands.brand_id','products.brand_id')->
        join('client_orders','client_orders.client_order_id','=','client_order_items.client_order_id')->
        join('branches','branches.branch_id','=','client_orders.branch_id');

        $reportSUMOrdersBrands = self::_reportPrepareSoldProductsConditions($reportSUMOrdersBrands, $attrs);

        return $reportSUMOrdersBrands->
        orderBy(\DB::raw('SUM(client_order_items.total_items_cost)'),'desc')->
        groupBy(\DB::raw("
                branches.branch_id,
                products.brand_id,
                client_order_items.operation_type,
                YEAR(client_order_items.created_at),
                MONTH(client_order_items.created_at)
            "))->get();

    }

    #endregion



}
