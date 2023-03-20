<?php

namespace App\models\supplier;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class suppliers_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "suppliers";

    protected $primaryKey = "sup_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'wallet_id', 'sup_name',
        'sup_phone', 'sup_company', 'sup_currency'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            suppliers.*
        "));

        if (isset($attrs["need_wallet_join"])) {
            $results = $results->addSelect(\DB::raw("
                wallet_obj.wallet_amount
            "))->
            join("wallets as wallet_obj", "wallet_obj.wallet_id", "=", "suppliers.wallet_id");
        }

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllSuppliers(array $attrs = [])
    {

        if (isset($attrs["request_data"])){

            if(isset($attrs["request_data"]["supplier_name"]) && !empty($attrs["request_data"]["supplier_name"])){
                $attrs["request_data"]["supplier_name"] = Vsi($attrs["request_data"]["supplier_name"]);
                $attrs["free_conds"][] = "suppliers.sup_name  = '". $attrs["request_data"]["supplier_name"] ."'";
            }

            if(isset($attrs["request_data"]["supplier_phone"]) && !empty($attrs["request_data"]["supplier_phone"])){
                $attrs["request_data"]["supplier_phone"] = Vsi($attrs["request_data"]["supplier_phone"]);
                $attrs["free_conds"][] = "suppliers.sup_phone  = '". $attrs["request_data"]["supplier_phone"] ."'";
            }

        }

        return self::getData($attrs);

    }

    public static function getSupplierDataById($supplier_id): Object
    {
        return self::find($supplier_id);
    }

    public static function getSupplierCurrencySymbolByWalletId($walletId)
    {
        $itemObj = self::where("wallet_id", $walletId)->first();
        if(!is_object($itemObj)){
            return null;
        }

        return $itemObj->sup_currency;
    }

    public static function totalSupplierWalletAmount(): Collection
    {

        return self::select(\DB::raw("
            SUM(wallets.wallet_amount) as suppliers_total_amount ,
             suppliers.sup_currency as currency
        "))->
        join('wallets','wallets.wallet_id', '=','suppliers.wallet_id')->
        groupBy(\DB::raw("
            suppliers.sup_currency
        "))->get();

    }

}
