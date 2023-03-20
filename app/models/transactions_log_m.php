<?php

namespace App\models;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class transactions_log_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "transactions_log";

    protected $primaryKey = "t_log_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'wallet_id', 'transaction_type', 'transaction_operation', 'transaction_currency',
        'transaction_amount', 'transaction_notes', 'money_type', 'is_refunded',
    ];

    public static $transactionTypes = [
        "expenses"              => "Expenses",
        "get_money_from_wallet" => "Withdraw Money",
        "add_money_to_wallet"   => "Deposit Money",
        "paycheck"              => "Paycheck",
    ];

    private static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            transactions_log.*
        "));


        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function checkIfTransactionsLogHaveSpecificWallet($wallet_id): bool
    {
        $logs = self::query()
            ->where('wallet_id', '=', $wallet_id)
            ->limit(1)
            ->get();

        if (!count($logs)) {
            return false;
        }
        return true;

    }

    private static function getTransactionLogsConds(array $attr = []): array
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        // filters
        if (isset($attr["transaction_id"]) && !empty($attr["transaction_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
                t_log_id = {$attr["transaction_id"]}
            ";
        }

        if (isset($attr["branch_name"]) && !empty($attr["branch_name"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
                (
                    branch_obj_cash.branch_name like '%{$attr["branch_name"]}%' OR
                    branch_obj_visa.branch_name like '%{$attr["branch_name"]}%'
                )
            ";
        }

        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               transactions_log.created_at > {$attr["date_from"]}
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               transactions_log.created_at < {$attr["date_to"]}
            ";
        }

        if (isset($attr["transaction_type"]) && !empty($attr["transaction_type"]) && $attr["transaction_type"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               transaction_type = '{$attr["transaction_type"]}'
            ";
        }

        if (isset($attr["money_type"]) && !empty($attr["money_type"]) && $attr["money_type"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               money_type = '{$attr["money_type"]}'
            ";
        }

        if (isset($attr["operation_type"]) && !empty($attr["operation_type"]) && $attr["operation_type"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               transaction_operation = '{$attr["operation_type"]}'
            ";
        }

        if (isset($attr["wallet_ids"]) && !empty($attr["wallet_ids"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               wallets.wallet_id in (" . $attr["wallet_ids"] . ")
            ";
        }


        return $modelUtilitiesAttrs;

    }

    public static function getTransactionLogs(array $attr = []): Collection
    {

        $results = self::select(\DB::raw("
            transactions_log.*
        "));

        $results = $results->
        join("wallets", "wallets.wallet_id", "=", "transactions_log.wallet_id")->
        orderBy("transactions_log.t_log_id", "desc");


        return ModelUtilities::general_attrs($results, self::getTransactionLogsConds($attr));

    }

    public static function getTransactionLogById($transaction_log_id): object
    {
        return self::getData([
            "free_conds" => [
                "t_log_id= $transaction_log_id",
            ],
            "return_obj" => "yes",
        ]);


    }

    public static function updateRow($rowId, $data)
    {

        self::where("t_log_id", $rowId)->update($data);

    }

}
