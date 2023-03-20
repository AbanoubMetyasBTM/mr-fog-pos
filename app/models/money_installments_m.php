<?php

namespace App\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class money_installments_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "money_installments";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'wallet_id', 'money_type', 'money_amount', 'should_receive_payment_at', 'is_received', 'img_obj'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            money_installments.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }



    private static function getMoneyInstallmentsConds(array $attr = []): array
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        // filters
        if (isset($attr["installment_id"]) && !empty($attr["installment_id"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
                id = {$attr["installment_id"]}
            ";
        }

        if (isset($attr["is_received"]) && $attr["is_received"] !== 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               is_received = '{$attr["is_received"]}'
            ";
        }

        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               money_installments.created_at > {$attr["date_from"]}
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               money_installments.created_at < {$attr["date_to"]}
            ";
        }
        return $modelUtilitiesAttrs;
    }


    public static function getMoneyInstallmentsByWalletId($wallet_id, array $attr =[]): Collection
    {

        $results = self::select(\DB::raw("
            money_installments.*
        "));


        $results = $results->where("wallet_id", "=", $wallet_id);

        return ModelUtilities::general_attrs($results, self::getMoneyInstallmentsConds($attr));

    }


    public static function updateMoneyInstallmentDate($installmentId, $date)
    {

        self::where('id', '=', $installmentId)->
        update(array(
            "should_receive_payment_at" => $date,
        ));

    }


    public static function updateMoneyInstallmentImg($installmentId, $img)
    {

        self::where('id', '=', $installmentId)->
        update(array(
            "img_obj" => $img,
        ));

    }

    public static function updateMoneyInstallmentIsReceive($installmentId)
    {

        self::where('id', '=', $installmentId)->
        update(array(
            "is_received" => 1,
        ));

    }


    #region reports

    public static function getMoneyDebt($attrs=[]): Collection
    {
        $attrs['limit']        = Vsi($attrs['limit'] ?? "");

        return self::select(\DB::raw("
            suppliers.sup_name,
            suppliers.sup_phone,
            suppliers.sup_currency,
            money_installments.*
        "))->
        join('suppliers','suppliers.wallet_id',
            '=',
            'money_installments.wallet_id'
        )->
        where('money_installments.money_type','=','debt')->
        where('money_installments.is_received','=',0)->
        orderBy('money_installments.should_receive_payment_at')->
        limit($attrs['limit'])->get();
    }

    public static function getMoneyOwed($attrs=[]): Collection
    {
        $attrs['limit']        = Vsi($attrs['limit'] ?? 10);

        return self::select(\DB::raw("
            clients.client_name,
            clients.client_email,
            clients.client_phone,
            branches.branch_currency,
            money_installments.*
        "))->
        join('clients','clients.wallet_id',
            '=',
            'money_installments.wallet_id'
        )->
        join('branches','branches.branch_id', '=', 'clients.branch_id')->
        where('money_installments.money_type','=','owed')->
        where('money_installments.is_received','=',0)->
        orderBy('money_installments.should_receive_payment_at')->
        limit($attrs['limit'])->
        get();
    }

    #endregion


}
