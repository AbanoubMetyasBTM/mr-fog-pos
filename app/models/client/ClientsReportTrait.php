<?php


namespace App\models\client;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ClientsReportTrait
{

    #region get-report-total-count-client-by-branch

    private static function _reportPrepareTotalClientCountConditions($result, $attrs)
    {

        $attrs['selected_year'] = Vsi($attrs['selected_year'] ?? "");
        $attrs['date_from']     = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']       = Vsi($attrs['date_to'] ?? "");


        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(clients.created_at) = '" . $attrs['selected_year'] . "'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {

            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(clients.created_at)'), [$attrs['date_from'], $attrs['date_to']]);
        }

        return $result;

    }

    public static function getReportTotalClientCountByBranchYearly($attrs)
    {

        $getReportTotalClientCountByBranchYearly = self::select(\DB::raw("
           Concat (
                branches.branch_name,
                ' - ',
                YEAR(clients.created_at)
            ) as 'item_name',
            count(clients.client_id) as 'total_clients'
        "))->
        join('branches', 'branches.branch_id', '=', 'clients.branch_id');

        $getReportTotalClientCountByBranchYearly = self::_reportPrepareTotalClientCountConditions($getReportTotalClientCountByBranchYearly, $attrs);

        return $getReportTotalClientCountByBranchYearly->
        orderBy(\DB::raw('count(clients.client_id)'), 'desc')->
        groupBy(\DB::raw("
                clients.branch_id,
                YEAR(clients.created_at)
            "))->
        get();

    }

    public static function getReportTotalClientCountByBranchMonthly($attrs)
    {

        $getReportTotalClientCountByBranchMonthly = self::select(\DB::raw("
           Concat (
                branches.branch_name,
                ' - ',
                YEAR(clients.created_at),
                ' - ',
                MONTH(clients.created_at)
            ) as 'item_name',
            count(clients.client_id) as 'total_clients'
        "))->
        join('branches', 'branches.branch_id', '=', 'clients.branch_id');

        $getReportTotalClientCountByBranchMonthly = self::_reportPrepareTotalClientCountConditions($getReportTotalClientCountByBranchMonthly, $attrs);

        return $getReportTotalClientCountByBranchMonthly->
        orderBy(\DB::raw('count(clients.client_id)'), 'desc')->
        groupBy(\DB::raw("
                clients.branch_id,
                YEAR(clients.created_at),
                MONTH(clients.created_at)
            "))->
        get();

    }


    public static function getReportTotalClientCountByBranch($attrs)
    {

        $getReportTotalClientCountByBranchMonthly = self::select(\DB::raw("
           Concat (
                branches.branch_name
            ) as 'item_name',
            count(clients.client_id) as 'total_clients'
        "))->
        join('branches', 'branches.branch_id', '=', 'clients.branch_id');

        $getReportTotalClientCountByBranchMonthly = self::_reportPrepareTotalClientCountConditions($getReportTotalClientCountByBranchMonthly, $attrs);

        return $getReportTotalClientCountByBranchMonthly->
        orderBy(\DB::raw('count(clients.client_id)'), 'desc')->
        groupBy(\DB::raw("
                clients.branch_id
            "))->
        get();

    }

    #endregion

    #region get-clients-count

    private static function _reportPrepareCountClientsConditions($result, $attrs)
    {

        $attrs['branch_id']      = Vsi($attrs['branch_id'] ?? "");
        $attrs['selected_year']  = Vsi($attrs['selected_year'] ?? "");
        $attrs['selected_month'] = Vsi($attrs['selected_month'] ?? "");
        $attrs['date_from']      = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']        = Vsi($attrs['date_to'] ?? "");

        if (!empty($attrs['branch_id'])) {
            $result = $result->
            whereRaw(\DB::raw("clients.branch_id = '" . $attrs['branch_id'] . "'"));
        }

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(clients.created_at) = '" . $attrs['selected_year'] . "'"));
        }

        if (!empty($attrs['selected_month'])) {
            $result = $result->
            whereRaw(\DB::raw("MONTH(clients.created_at) = '" . $attrs['selected_month'] . "'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {
            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween('clients.created_at', [$attrs["date_from"], $attrs["date_to"]]);
        }

        return $result;

    }

    public static function getCountClients($attrs)
    {

        $getClientsCount = self::select(\DB::raw("
            count(clients.client_id) as 'total_clients'
        "));

        $getClientsCount = self::_reportPrepareCountClientsConditions($getClientsCount, $attrs);

        return $getClientsCount->
        first();
    }

    #endregion

    public static function totalClientWalletAmount($branchId = null): Collection
    {

        $res = self::select(\DB::raw("
            SUM(wallets.wallet_amount) as total_amount_clients,
            branches.branch_currency
        "))->
        join('wallets', 'wallets.wallet_id', '=', 'clients.wallet_id')->
        join('branches', 'branches.branch_id', '=', 'clients.branch_id');

        if ($branchId != null) {
            $branchId = Vsi($branchId);
            $res      = $res->where("branches.branch_id", "=", $branchId);
        }

        return $res->groupBy(\DB::raw("
            branches.branch_currency
        "))->get();

    }


}
