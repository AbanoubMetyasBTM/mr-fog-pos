<?php

namespace App\models\supplier;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;

class suppliers_orders_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "supplier_orders";

    protected $primaryKey = "supplier_order_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'original_order_id', 'original_order_img_obj', 'branch_id',
        'supplier_id', 'employee_id', 'total_items_cost', 'total_taxes',
        'additional_fees_desc', 'additional_fees', 'total_return_amount',
        'total_cost', 'order_status', 'ordered_at', 'paid_amount'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            supplier_orders.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function checkIfSupplierHasOrders($supplierId): bool
    {
        $orders =
            self::query()
                ->where('supplier_id', '=', $supplierId)
                ->get();

        if (!count($orders)) {
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

        if (!count($orders)) {
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

        if (!count($orders)) {
            return false;
        }
        return true;
    }

    public static function getAllSuppliersOrders($orderId = null, $attrs = [])
    {
        $attrs["order_by"] = ["supplier_orders.supplier_order_id", "desc"];

        $results = self::select(\DB::raw("
            supplier_orders.*,
            branches.branch_name,
            suppliers.sup_name,
            users.full_name as emp_name
        "));

        $results = $results->
        leftJoin('branches', 'branches.branch_id', '=', 'supplier_orders.branch_id')->
        join('suppliers', 'suppliers.sup_id', 'supplier_orders.supplier_id')->
        join('users', 'users.user_id', 'supplier_orders.employee_id');

        if (!is_null($orderId)) {
            $results             = $results->where('supplier_orders.supplier_order_id', '=', $orderId);
            $attrs["return_obj"] = "yes";
        }


        return ModelUtilities::general_attrs($results, self::getAllSuppliersOrdersConds($attrs));
    }


    private static function getAllSuppliersOrdersConds($attr = [])
    {
        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];

        // filters
        if (isset($attr["branch_id"]) && !empty($attr["branch_id"]) && $attr["branch_id"] != "all") {

            if ($attr["branch_id"] == "null") {
                $attr["branch_id"] = null;
            }
            $modelUtilitiesAttrs["free_conds"][] = "
                supplier_orders.branch_id = {$attr["branch_id"]}
            ";
        }

        if (isset($attr["employee_id"]) && !empty($attr["employee_id"]) && $attr["employee_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.employee_id = {$attr["employee_id"]}
            ";
        }

        if (isset($attr["sup_id"]) && !empty($attr["sup_id"]) && $attr["sup_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.supplier_id = {$attr["sup_id"]}
            ";
        }

        if (isset($attr["order_status"]) && !empty($attr["order_status"]) && $attr["order_status"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.order_status = {$attr["order_status"]}
            ";
        }

        if (isset($attr["original_order_id"]) && !empty($attr["original_order_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.original_order_id = {$attr["original_order_id"]}
            ";
        }

        if (isset($attr["order_id"]) && !empty($attr["order_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.supplier_order_id = {$attr["order_id"]}
            ";
        }

        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.created_at > {$attr["date_from"]}
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               supplier_orders.created_at < {$attr["date_to"]}
            ";
        }

        if (isset($attr["paginate"]) && !empty($attr["paginate"])) {
            $modelUtilitiesAttrs["paginate"] = $attr["paginate"];
        }

        if (isset($attr["return_obj"]) && !empty($attr["return_obj"])) {
            $modelUtilitiesAttrs["return_obj"] = $attr["return_obj"];
        }

        $modelUtilitiesAttrs["order_by"] = ["supplier_orders.supplier_order_id", "desc"];


        return $modelUtilitiesAttrs;
    }


    public static function saveSupplierOrder($data, $orderId = null)
    {
        if (is_null($orderId)) {
            return self::create($data);
        }
        else {
            return self::where('supplier_order_id', '=', $orderId)->
            update($data);
        }

    }


    public static function changeOrderStatus($orderId, $status)
    {
        // $status => done, cancel
        self::where('supplier_order_id', '=', $orderId)->update(["order_status" => $status]);
    }


    public static function updateTotalReturnAmount($orderId, $returnAmount)
    {
        $order = self::where("supplier_order_id", "=", $orderId)->first();

        $newTotalReturnAmount = round(floatval($order->total_return_amount) + floatval($returnAmount), 2);

        self::where("supplier_order_id", "=", $orderId)
            ->update(["total_return_amount" => $newTotalReturnAmount]);
    }


    public static function getOrderById($orderId): object
    {
        return self::getData([
            "free_conds" => [
                "supplier_order_id = $orderId"
            ],
            "return_obj" => "yes"
        ]);

    }

    public static function updateOrderToMakeDone($orderId, $attrs)
    {

        self::where("supplier_order_id", "=", $orderId)->
        update([
            'additional_fees'      => (float)$attrs['additional_fees'],
            'additional_fees_desc' => $attrs['additional_fees_desc'],
            'total_cost'           => (float)$attrs['total_cost'],
            'paid_amount'          => (float)$attrs['paid_amount'],
            'order_status'         => 'done'
        ]);

    }
}
