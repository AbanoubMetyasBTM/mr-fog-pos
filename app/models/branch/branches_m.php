<?php

namespace App\models\branch;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class branches_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "branches";

    protected $primaryKey = "branch_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
       'cash_wallet_id', 'debit_card_wallet_id', 'credit_card_wallet_id',
       'cheque_wallet_id', 'tax_group_id', 'branch_api_access_token', 'branch_name',
       'branch_img_obj', 'branch_country', 'branch_currency', 'branch_timezone',
       'first_day_of_the_week', 'return_policy_days'
    ];



    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            branches.*,
            taxes_groups.group_taxes as 'branch_taxes'
        "));

        if(isset($attrs["need_wallet_join"])){
            $results = $results->addSelect(\DB::raw("
                cash_wallet.wallet_amount as 'cash_wallet_amount',
                debit_card_wallet.wallet_amount as 'debit_card_wallet_amount',
                credit_card_wallet.wallet_amount as 'credit_card_wallet_amount',
                cheque_wallet.wallet_amount as 'cheque_wallet_amount'
            "))->
            join("wallets as cash_wallet", "cash_wallet.wallet_id", "=", "branches.cash_wallet_id")->
            join("wallets as debit_card_wallet", "debit_card_wallet.wallet_id", "=", "branches.debit_card_wallet_id")->
            join("wallets as credit_card_wallet", "credit_card_wallet.wallet_id", "=", "branches.credit_card_wallet_id")->
            join("wallets as cheque_wallet", "cheque_wallet.wallet_id", "=", "branches.cheque_wallet_id");
        }

        $results = $results->join("taxes_groups", "taxes_groups.group_id","=","branches.tax_group_id");

        return ModelUtilities::general_attrs($results, $attrs);

    }



    public static function getAllBranchesOrCurrentBranchOnly($attrs=[]): Collection
    {

        if (!isset($attrs["free_conds"])){
            $attrs["free_conds"] = [];
        }

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "branches.branch_id = $currentBranchId";
        }

        return self::getData($attrs);

    }

    public static function getAllBranches($attrs=[]): Collection
    {
        return self::getData($attrs);
    }

    public static function getBranchWalletsId(int $walletId)
    {

        return self::getData([
            "free_conds" => [
                "
                    (
                        cash_wallet_id = $walletId OR
                        debit_card_wallet_id = $walletId OR
                        credit_card_wallet_id = $walletId OR
                        cheque_wallet_id = $walletId
                    )
                "
            ],
            "return_obj" => "yes"
        ]);

    }


    public static function getBranchById($branch_id)
    {
        return self::getData([
            "free_conds" => [
                "branch_id = $branch_id"
            ],
            "return_obj" => "yes"
        ]);

    }

    public static function getBranchesByIds($branchesIds)
    {
         return self::getData([
            "whereIn" => [
                "branch_id" => $branchesIds
            ]
        ]);
    }

    public static function getBranchCurrencySymbolByWalletId($walletId)
    {

        $itemObj = branches_m::getBranchWalletsId($walletId);
        if (!is_object($itemObj)){
            return null;
        }

        return $itemObj->branch_currency;

    }

    public static function getBranchFromCache(int $branchId)
    {
        $branchObj = \Cache::get("branch_data_${branchId}");

        if($branchObj == null) {
            $branchObj = self::getBranchById($branchId);
            \Cache::put("branch_data_${branchId}", $branchObj);
        }

        return $branchObj;
    }

    public static function removeBranchFromCache(int $branchId){
        \Cache::forget("branch_data_${branchId}");
    }

    public static function checkItHasAtLeastOneRow(): bool
    {
        $res = self::limit(1)->first();
        if(is_object($res)){
            return true;
        }
        return false;
    }

    public static function getBranchByGroupId($group_id)
    {

        return self::
        where('branches.tax_group_id','=',$group_id)->
        limit(1)->
        first();

    }


}
