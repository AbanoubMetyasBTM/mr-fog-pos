<?php

namespace App\models\client;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class clients_m extends \Eloquent
{

    use SoftDeletes, ClientsReportTrait;

    protected $table = "clients";

    protected $primaryKey = "client_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'user_id', 'branch_id', 'wallet_id', 'points_wallet_id',
        'tax_group_id', 'client_type', 'client_name',
        'client_email', 'client_phone', 'client_total_orders_count',
        'client_total_orders_amount'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            clients.*,
            taxes_groups.group_taxes
        "));

        $results = $results->leftJoin("taxes_groups", "taxes_groups.group_id","=","clients.tax_group_id");


        if(isset($attrs["need_branch_join"])){
            $results = $results->addSelect(\DB::raw("
                branches.branch_name,
                branches.branch_currency
            "))->
            join("branches", "branches.branch_id", "=", "clients.branch_id");
        }

        if(isset($attrs["need_wallet_join"])){
            $results = $results->addSelect(\DB::raw("
                wallets.wallet_amount,
                points_wallet.wallet_amount as 'points_wallet_value'
            "))->
            join("wallets", "wallets.wallet_id", "=", "clients.wallet_id")->
            leftJoin("wallets as points_wallet", "points_wallet.wallet_id", "=", "clients.points_wallet_id");
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllClientsOrSpecificBranch(array $attrs = [])
    {

        if (!isset($attrs["free_conds"])){
            $attrs["free_conds"] = [];
        }


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "clients.branch_id = $currentBranchId";
        }

        if (isset($attrs["request_data"])){

            $reqData = $attrs["request_data"];

            if(isset($reqData["order_by"]) && !empty($reqData["order_by"])){

                $reqData["order_by"] = Vsi($reqData["order_by"]);

                if (in_array($reqData["order_by"],["client_total_orders_amount","client_total_orders_count"])){
                    $attrs["order_by"] = ["clients.".$reqData["order_by"], "desc"];
                }

            }

            $fieldsConds = [
                "client_type","client_phone","client_name"
            ];

            foreach ($fieldsConds as $cond) {
                if (isset($reqData[$cond]) && !empty($reqData[$cond])) {
                    $reqData[$cond]        = Vsi($reqData[$cond]);
                    $attrs["free_conds"][] = "clients.{$cond} = '" . $reqData["client_type"] . "'";
                }
            }

        }


        return self::getData($attrs);

    }

    public static function getAllClients(array $attrs = []): Collection
    {
        return self::getData($attrs);
    }

    public static function getClientDataById($client_id)
    {
        return self::getData([
            "need_wallet_join" => true,
            "free_conds" => [
                "client_id = $client_id",
            ],
            "return_obj" => "yes"
        ]);
    }

    public static function getClientByNameOrPhone($attr)
    {

        $attrs = [];
        $attrs["need_wallet_join"] = "true";
        $attrs["free_conds"]       = [];
        $attrs["free_conds"][]     = " (client_phone like '%${attr}%' OR client_name like '%${attr}%') ";


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "clients.branch_id = $currentBranchId";
        }

        return self::getData($attrs);
    }

    public static function getClientCurrencySymbolByWalletId($walletId)
    {
        $itemObj = clients_m::getData([
            "free_conds"       => [
                "clients.wallet_id = $walletId"
            ],
            "need_branch_join" => "yes",
            "return_obj"       => "yes",
        ]);

        if(!is_object($itemObj)){
            return null;
        }

        return $itemObj->branch_currency;
    }

    public static function updateClientData($clientId, $data)
    {
        self::where('client_id', '=', $clientId)->update($data);

    }


    public static function getClientByGroupId($group_id)
    {

       return self::
            where('clients.tax_group_id','=',$group_id)->
            limit(1)->
            first();

    }


}
