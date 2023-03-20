<?php

namespace App\models\client;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class clients_orders_m extends \Eloquent
{

    use SoftDeletes, ClientOrdersReportsTrait;

    protected $table = "client_orders";

    protected $primaryKey = "client_order_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'branch_id', 'client_id', 'client_user_id', 'employee_id', 'register_id', 'register_session_id',
        'total_items_cost',

        'wallet_paid_amount', 'gift_card_id',
        'gift_card_paid_amount', 'cash_paid_amount',
        'debit_card_paid_amount', 'debit_card_receipt_img_obj',
        'credit_card_paid_amount', 'credit_card_receipt_img_obj',
        'cheque_paid_amount', 'cheque_card_receipt_img_obj',

        'used_coupon_id', 'used_coupon_value',

        'used_points_redeem_points', 'used_points_redeem_money',

        'can_not_return_items',
        'wallet_return_amount','gift_card_return_amount',
        'cash_return_amount', 'debit_card_return_amount', 'credit_card_return_amount',

        'cheque_return_amount',
        'total_return_amount', 'total_paid_amount',
        'total_taxes', 'total_cost',
        'order_status', 'order_type', 'order_timezone', 'pick_up_date',
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            client_orders.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function checkIfClientHasOrders($clientId) : bool
    {
        $orders =
            self::query()
                ->where('client_id', '=', $clientId)
                ->get();

        if (!count($orders)){
            return false;
        }
        return true;
    }

    public static function checkIfRegisterHasOrders($registerId): bool
    {
        $orders =
            self::query()
                ->where('register_id', '=', $registerId)
                ->get();

        if (!count($orders)){
            return false;
        }
        return true;
    }

    public static function checkIfBranchHasOrders($branchId): bool
    {
        $orders =
            self::query()
                ->where('branch_id', '=', $branchId)
                ->get();

        if (!count($orders)){
            return false;
        }
        return true;
    }

    public static function checkIfEmployeeHasOrders($empId): bool
    {
        $orders =
            self::query()
                ->where('employee_id', '=', $empId)
                ->limit(1)
                ->get();

        if (!count($orders)){
            return false;
        }
        return true;
    }

    public static function getAllClientOrders($orderId = null, $attrs=[])
    {

        $attrs["order_by"] = ["client_orders.client_order_id", "desc"];

        $results = self::select(\DB::raw("
            client_orders.*,
            branches.branch_name,
            branches.branch_currency,
            branches.branch_img_obj,
            clients.client_name,
            clients.client_id,
            users.full_name as emp_name,
            registers.register_name,
            wallets.wallet_amount,
            gift_cards.card_unique_number
        "));

        $results = $results->
            join('branches','branches.branch_id','=','client_orders.branch_id')->
            join('clients','clients.client_id','client_orders.client_id')->
            join('wallets','wallets.wallet_id','=', 'clients.wallet_id')->
            join('users','users.user_id','client_orders.employee_id')->
            join('registers','registers.register_id','=','client_orders.register_id')->
            leftJoin('gift_cards','gift_cards.card_id','=','client_orders.gift_card_id');

        if (!is_null($orderId)) {
            $results             = $results->where('client_orders.client_order_id', '=', $orderId);
            $attrs["return_obj"] = "yes";
        }


        return ModelUtilities::general_attrs($results, self::getAllClientsOrdersConds($attrs));
    }

    private static function getAllClientsOrdersConds($attrs=[])
    {
        $modelUtilitiesAttrs               = $attrs;
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];

        // filters
        if (isset($attrs["branch_id"]) && !empty($attrs["branch_id"]) && $attrs["branch_id"] != "all") {

            if ($attrs["branch_id"] == "null"){
                $attrs["branch_id"] = null;
            }
            $modelUtilitiesAttrs["free_conds"][] = "
                client_orders.branch_id = {$attrs["branch_id"]}
            ";
        }

        if (isset($attrs["employee_id"]) && !empty($attrs["employee_id"]) && $attrs["employee_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.employee_id = {$attrs["employee_id"]}
            ";
        }

        if (isset($attrs["client_id"]) && !empty($attrs["client_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.client_id = {$attrs["client_id"]}
            ";
        }

        if (isset($attrs["order_status"]) && !empty($attrs["order_status"]) && $attrs["order_status"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.order_status = '{$attrs["order_status"]}'
            ";
        }

        if (isset($attrs["original_order_id"]) && !empty($attrs["original_order_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.original_order_id = {$attrs["original_order_id"]}
            ";
        }

        if (isset($attrs["order_id"]) && !empty($attrs["order_id"])) {
            $attrs['order_id'] = Vsi($attrs['order_id'] ?? "");

            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.client_order_id = {$attrs["order_id"]}
            ";
        }


        if (isset($attrs["date_from"]) && !empty($attrs["date_from"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.created_at >= '{$date}'
            ";
        }

        if (isset($attrs["date_to"]) && !empty($attrs["date_to"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               client_orders.created_at <= '{$date}'
            ";
        }

        if (isset($attrs["paginate"]) && !empty($attrs["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attrs["paginate"];
        }

        return $modelUtilitiesAttrs;
    }

    public static function changeOrderStatus($orderId, $status)
    {
        // $status => done, cancel
        self::where('client_order_id', '=', $orderId)->update(["order_status" => $status]);
    }

    public static function updateOrderData($data, $orderId)
    {
        return self::where('client_order_id', '=', $orderId)->
                     update($data);

    }


}
