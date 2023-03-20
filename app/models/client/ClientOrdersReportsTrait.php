<?php


namespace App\models\client;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ClientOrdersReportsTrait
{


    #region getTotalCountOrdersByBranch

    private static function _reportTotalCountOrdersByBranchConditions($result, $attrs)
    {

        $attrs['selected_year'] = Vsi($attrs['selected_year'] ?? "");
        $attrs['date_from']     = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']       = Vsi($attrs['date_to'] ?? "");


        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(client_orders.created_at) = '" . $attrs['selected_year'] . "'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {

            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(client_orders.created_at)'), [$attrs["date_from"], $attrs["date_to"]]);
        }

        return $result;
    }

    public static function getReportTotalCountOrdersByBranch($attrs)
    {

        $getTotalCountOrdersByBranch = self::select(DB::raw('
            count(client_orders.client_order_id) as total_count,
            branches.branch_currency,
            branches.branch_name as item_name
        '))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getTotalCountOrdersByBranch = self::_reportTotalCountOrdersByBranchConditions($getTotalCountOrdersByBranch, $attrs);

        return $getTotalCountOrdersByBranch->
        orderBy(DB::raw('
            count(client_orders.client_order_id)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id
        "))->
        get();

    }

    public static function getReportTotalOrdersByBranchYearly($attrs)
    {

        $getTotalOrdersByBranchYearly = self::select(
            DB::raw("
            count(client_orders.client_order_id) as total_count,
            branches.branch_currency,
            concat (
                branches.branch_name,
                ' - ',
                 YEAR(client_orders.created_at)

            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');


        $getTotalCountOrdersByBranch = self::_reportTotalCountOrdersByBranchConditions($getTotalOrdersByBranchYearly, $attrs);

        return $getTotalCountOrdersByBranch->
        orderBy(DB::raw('
            count(client_orders.client_order_id)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at)
        "))->
        get();

    }

    public static function getReportTotalOrdersByBranchMonthly($attrs)
    {

        $getTotalOrdersByBranchYearly = self::select(
            DB::raw("
            count(client_orders.client_order_id) as total_count,
            branches.branch_currency,
            concat (
                branches.branch_name,
                ' - ',
                YEAR(client_orders.created_at),
                ' - ',
                MONTH(client_orders.created_at)

            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');
        $getTotalCountOrdersByBranch  = self::_reportTotalCountOrdersByBranchConditions($getTotalOrdersByBranchYearly, $attrs);

        return $getTotalCountOrdersByBranch->
        orderBy(DB::raw('
            count(client_orders.client_order_id)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at),
            MONTH(client_orders.created_at)
        "))->
        get();

    }

    #endregion

    #region report-group-client-orders-by-branch

    private static function _reportTotalSumOrdersByBranchConditions($result, $attrs)
    {

        $attrs['selected_year'] = Vsi($attrs['selected_year'] ?? "");
        $attrs['date_from']     = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']       = Vsi($attrs['date_to'] ?? "");

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(client_orders.created_at) = '" . $attrs['selected_year'] . "'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {

            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(client_orders.created_at)'), [$attrs["date_from"], $attrs["date_to"]]);
        }

        return $result;

    }

    #region getTotalQuantityOrdersByBranch

    public static function getReportTotalSumOrdersByBranchYearly($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ',
                YEAR(client_orders.created_at)
            ) as item_name
        "))->join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('
            SUM(client_orders.total_cost-client_orders.total_return_amount)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at)
        "))->
        get();

    }

    public static function getReportTotalSumOrdersByBranchMonthly($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ' ,
                YEAR(client_orders.created_at),
                ' - ',
                MONTH(client_orders.created_at)
            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('
               SUM(client_orders.total_cost-client_orders.total_return_amount)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at),
            MONTH(client_orders.created_at)
        "))->
        get();

    }

    public static function getReportTotalSumOrdersByBranch($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ' ,
                YEAR(client_orders.created_at),
                ' - ',
                MONTH(client_orders.created_at)
            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('SUM(client_orders.total_cost-client_orders.total_return_amount)'), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id
        "))->
        get();

    }

    #endregion

    #region getTotalQuantityOrdersByBranch

    public static function getReportOrdersBrandsSalesSumYearly($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ',
                YEAR(client_orders.created_at)
            ) as item_name
        "))->join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('
            SUM(client_orders.total_cost-client_orders.total_return_amount)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at)
        "))->
        get();

    }

    public static function getReportOrdersBrandsSalesSumMonthly($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ' ,
                YEAR(client_orders.created_at),
                ' - ',
                MONTH(client_orders.created_at)
            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('
               SUM(client_orders.total_cost-client_orders.total_return_amount)
        '), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id,
            YEAR(client_orders.created_at),
            MONTH(client_orders.created_at)
        "))->
        get();

    }

    public static function getReportOrdersBrandsSalesSum($attrs)
    {

        $getReportTotalSumOrdersByBranch = self::select(DB::raw("
            SUM(client_orders.total_cost-client_orders.total_return_amount) as total_sum,
            branches.branch_currency,
             concat (
                branches.branch_name,
                ' - ' ,
                YEAR(client_orders.created_at),
                ' - ',
                MONTH(client_orders.created_at)
            ) as item_name
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id');

        $getReportTotalSumOrdersByBranch = self::_reportTotalSumOrdersByBranchConditions($getReportTotalSumOrdersByBranch, $attrs);

        return $getReportTotalSumOrdersByBranch->orderBy(DB::raw('SUM(client_orders.total_cost-client_orders.total_return_amount)'), 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id
        "))->
        get();

    }

    #endregion

    #endregion


    private static function _reportTotalCountOrdersConditions($result, $attrs)
    {

        $attrs['branch_id']      = Vsi($attrs['branch_id'] ?? "");
        $attrs['selected_year']  = Vsi($attrs['selected_year'] ?? "");
        $attrs['selected_month'] = Vsi($attrs['selected_month'] ?? "");
        $attrs['date_from']      = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']        = Vsi($attrs['date_to'] ?? "");


        if (!empty($attrs['branch_id'])) {
            $result = $result->
            whereRaw(\DB::raw("client_orders.branch_id = '" . $attrs['branch_id'] . "'"));
        }

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(client_orders.created_at) = '" . $attrs['selected_year'] . "'"));
        }

        if (!empty($attrs['selected_month'])) {
            $result = $result->
            whereRaw(\DB::raw("MONTH(client_orders.created_at) = '" . $attrs['selected_month'] . "'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {
            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(client_orders.created_at)'), [$attrs["date_from"], $attrs["date_to"]]);
        }

        return $result;

    }

    public static function getClientOrdersCount($attrs = [])
    {

        $getTotalCountOrders = self::select(DB::raw('
            count(client_orders.client_order_id) as total_count
        '));

        $getTotalCountOrdersByBranch = self::_reportTotalCountOrdersConditions(
            $getTotalCountOrders,
            $attrs
        );
        return $getTotalCountOrdersByBranch->
        first();

    }


    public static function getOrdersGroupedByBranches($attrs = []): Collection
    {

        $attrs['date_from'] = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']   = Vsi($attrs['date_to'] ?? "");

        $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
        $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

        return self::select(
            DB::raw("
                count(client_orders.client_order_id) as total_count,
                branches.branch_name,
                branches.branch_id,
                branches.branch_img_obj
        "))->
        join('branches', 'branches.branch_id', 'client_orders.branch_id')->
        whereBetween(DB::raw('DATE(client_orders.created_at)'), [$attrs["date_from"], $attrs["date_to"]])->
        orderBy('client_orders.client_order_id', 'desc')->
        groupBy(DB::raw("
            client_orders.branch_id
        "))->get();

    }


}
