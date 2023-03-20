<?php

namespace App\models\gift_card;

use App\models\ModelUtilities;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Type;

class gift_cards_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "gift_cards";

    protected $primaryKey = "card_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
       'card_template_id', 'wallet_id', 'branch_id', 'employee_id', 'register_id',
       'register_session_id', 'client_id', 'card_title', 'card_unique_number',
       'card_expiration_date', 'card_price', 'wallet_paid_amount','cash_paid_amount',
       'debit_card_paid_amount', 'debit_card_receipt_img_obj', 'credit_card_paid_amount',
       'credit_card_receipt_img_obj', 'cheque_paid_amount', 'cheque_card_receipt_img_obj',
       'gift_card_timezone',
    ];



    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            gift_cards.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllGiftCards($attrs=[])
    {
        $results = self::select(\DB::raw("
            gift_cards.*,
            branches.branch_currency,
            branches.branch_name,
            wallets.wallet_amount as remained_amount,
            users.full_name,
            clients.client_name,
            registers.register_name,
            gift_card_templates.template_title

        "))->join("branches", "branches.branch_id", "=", "gift_cards.branch_id")->
            join("wallets", "wallets.wallet_id", "=", "gift_cards.wallet_id")->
            join("users", "users.user_id", "=", "gift_cards.employee_id")->
            join("clients", "clients.client_id", "=", "gift_cards.client_id")->
            join('registers','registers.register_id','=','gift_cards.register_id')->
            join('gift_card_templates','gift_card_templates.template_id','=','gift_cards.card_template_id');

        $attrs['order_by'] = ['gift_cards.card_id', 'desc'];

        return ModelUtilities::general_attrs($results, self::getAllGiftCardsConds($attrs));
    }


    private static function getAllGiftCardsConds($attrs)
    {
        $modelUtilitiesAttrs               = $attrs;
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        // filters
        if (isset($attrs["branch_id"]) && !empty($attrs["branch_id"]) && $attrs["branch_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
                gift_cards.branch_id = {$attrs["branch_id"]}
            ";
        }

        if (isset($attrs["card_id"]) && !empty($attrs["card_id"]) && $attrs["card_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
                gift_cards.card_id = {$attrs["card_id"]}
            ";
        }

        if (isset($attrs["employee_id"]) && !empty($attrs["employee_id"]) && $attrs["employee_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               gift_cards.employee_id = {$attrs["employee_id"]}
            ";
        }

        if (isset($attrs["client_id"]) && !empty($attrs["client_id"]) && $attrs["client_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               gift_cards.client_id = {$attrs["client_id"]}
            ";
        }

        if (isset($attrs["date_from"]) && !empty($attrs["date_from"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               gift_cards.created_at >= '{$attrs["date_from"]}'
            ";
        }

        if (isset($attrs["date_to"]) && !empty($attrs["date_to"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               gift_cards.created_at <= '{$attrs["date_to"]}'
            ";
        }

        if (isset($attrs["card_number"]) && !empty($attrs["card_number"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               gift_cards.card_unique_number = '{$attrs["card_number"]}'
            ";
        }

        if (isset($attrs["paginate"]) && !empty($attrs["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attrs["paginate"];
        }

        if (isset($attrs["return_obj"])){
            $modelUtilitiesAttrs["return_obj"] = "yes";
        }

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $modelUtilitiesAttrs["free_conds"][] = "
                gift_cards.branch_id = {$currentBranchId}
            ";

        }

        return $modelUtilitiesAttrs;
    }

    public static function getGiftCardById($giftCardId)
    {
        return self::getData([
            "free_conds" => [
                "card_id = $giftCardId"
            ],
            "return_obj" => "yes"
        ]);
    }

    public static function getGiftCardByUniqueNumber($cardUniqueNum)
    {
        $currentTime = date('Y-m-d', strtotime(now()));

        return self::select(\DB::raw("
                gift_cards.*,
                wallets.wallet_amount
            "))->
            join('wallets','wallets.wallet_id', '=','gift_cards.wallet_id')->
            where('card_unique_number','like', $cardUniqueNum)->
            where('card_expiration_date', '>', $currentTime)->
            first();
    }

    #region get-count-cards

    private static function _reportPrepareCountCardsConditions($result,$attrs){

        $attrs['branch_id']      = Vsi($attrs['branch_id'] ?? "");
        $attrs['selected_year']  = Vsi($attrs['selected_year'] ?? "");
        $attrs['selected_month'] = Vsi($attrs['selected_month'] ?? "");
        $attrs['date_from']      = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']        = Vsi($attrs['date_to'] ?? "");

        if (!empty($attrs['branch_id'])) {
            $result = $result->
            whereRaw(\DB::raw("gift_cards.branch_id = '".$attrs['branch_id']."'"));
        }

        if (!empty($attrs['selected_year'])) {
            $result = $result->
            whereRaw(\DB::raw("YEAR(gift_cards.created_at) = '".$attrs['selected_year']."'"));
        }

        if (!empty($attrs['selected_month'])) {
            $result = $result->
            whereRaw(\DB::raw("MONTH(gift_cards.created_at) = '".$attrs['selected_month']."'"));
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])) {
            $attrs['date_from'] = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']   = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween('gift_cards.created_at', [$attrs["date_from"], $attrs["date_to"]]);
        }

        return $result;

    }

    public static function getCountCards($attrs = [])
    {
        $getClientsCount = self::select(\DB::raw("
            count(gift_cards.card_id) as 'total_gift_cards'
        "));

        $getClientsCount = self::_reportPrepareCountCardsConditions($getClientsCount, $attrs);

        return $getClientsCount->
        first();
    }

    #endregion


    public static function totalGiftCardsWalletAmount($branchId = null): Collection
    {

        $res =  self::select(\DB::raw("
            SUM(wallets.wallet_amount) as total_amount_gift_cards,
            branches.branch_currency
        "))->
        join('wallets', 'wallets.wallet_id', '=', 'gift_cards.wallet_id')->
        join('branches', 'branches.branch_id', '=', 'gift_cards.branch_id');

        if ($branchId != null) {
            $branchId = Vsi($branchId);
            $res      = $res->where("branches.branch_id", "=", $branchId);
        }

        return $res->groupBy(\DB::raw("
            branches.branch_currency
        "))->get();

    }

    public static function checkIfTemplateIsUsed(int $templateId):bool
    {
        $obj = self::where("card_template_id", $templateId)->limit(1)->get()->first();

        if(is_object($obj)){
            return true;
        }

        return false;
    }

}
