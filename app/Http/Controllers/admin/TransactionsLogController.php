<?php

namespace App\Http\Controllers\admin;

use App\Events\Wallets\DepositMoneyForWallet;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Http\Controllers\AdminBaseController;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\supplier\suppliers_m;
use App\models\transactions_log_m;
use App\models\wallets_m;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsLogController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Transactions Log");
    }

    public function index(Request $request, $walletOwnerName, $walletIds)
    {

        havePermissionOrRedirect("admin/transactions_log", "show_log");

        $this->data["request_data"]      = (object)$request->all();
        $conds                           = $request->all();
        $walletIds                       = explode(',', $walletIds);
        $walletIds                       = array_diff($walletIds, ['']);

        $conds["wallet_ids"]             = implode(',', $walletIds);
        $this->data["results"]           = transactions_log_m::getTransactionLogs($conds);
        $this->data["wallet_owner_name"] = $walletOwnerName;

        return $this->returnView($request, "admin.subviews.transactions_log.show");
    }

    private function getWalletCurrencySymbol($walletId){
        $currencySymbol = clients_m::getClientCurrencySymbolByWalletId($walletId);
        if (!empty($currencySymbol)){
            return $currencySymbol;
        }

        $currencySymbol = suppliers_m::getSupplierCurrencySymbolByWalletId($walletId);
        if (!empty($currencySymbol)){
            return $currencySymbol;
        }

        $currencySymbol = branches_m::getBranchCurrencySymbolByWalletId($walletId);
        if (!empty($currencySymbol)){
            return $currencySymbol;
        }

        abort(404);
        die();
    }

    public function depositMoneyToValidation(Request $request)
    {

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["money_amount"] = clean($request->get("money_amount"));
        $rules_values["admin_notes"]  = clean($request->get("admin_notes"));

        $rules_itself["money_amount"] = "required|numeric|min:1";
        $rules_itself["admin_notes"]  = "required";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function depositMoney(Request $request, $walletOwnerName, $walletId)
    {

        havePermissionOrRedirect("admin/transactions_log", "deposit_money");

        $walletObj                       = wallets_m::findOrFail($walletId);
        $this->data["item_data"]         = $walletObj;
        $this->data["wallet_owner_name"] = $walletOwnerName;
        $currencySymbol                  = $this->getWalletCurrencySymbol($walletId);
        $this->data["currency_symbol"]   = $currencySymbol;

        if ($request->method() == "POST") {

            $validator = $this->depositMoneyToValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            $moneyAmount = clean($request->get("money_amount"));
            $notes  = clean($request->get("admin_notes"));

            if (strlen($notes) < 0){
                $notes = "added money ($moneyAmount) to ($walletOwnerName)";
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Transactions-Log',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'deposit-Money',
                'old_obj'        => $walletObj,
            ]);

            event(new DepositMoneyForWallet(
                $this->user_id,
                $walletId,
                $walletOwnerName,
                $currencySymbol,
                $moneyAmount,
                $notes,
                true
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/transactions-log/show-log/$walletOwnerName/$walletId",
                "the system is process your request now, once it is done we'll send you an email",
                true
            );

        }

        return $this->returnView($request, "admin.subviews.transactions_log.deposit_money");


    }



    public function withdrawMoneyFromValidation(Request $request)
    {

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["money_amount"] = clean($request->get("money_amount"));
        $rules_values["admin_notes"]  = clean($request->get("admin_notes"));

        $rules_itself["money_amount"] = "required|numeric|min:1";
        $rules_itself["admin_notes"]  = "required";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function withdrawMoney(Request $request, $walletOwnerName, $walletId)
    {

        havePermissionOrRedirect("admin/transactions_log", "withdraw_money");

        $walletObj                       = wallets_m::findOrFail($walletId);
        $this->data["item_data"]         = $walletObj;
        $this->data["wallet_owner_name"] = $walletOwnerName;
        $currencySymbol                  = $this->getWalletCurrencySymbol($walletId);
        $this->data["currency_symbol"]   = $currencySymbol;

        if ($request->method() == "POST") {

            $validator = $this->withdrawMoneyFromValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            $moneyAmount  = clean($request->get("money_amount"));
            $notes        = clean($request->get("admin_notes"));

            if (strlen($notes) < 0){
                $notes = "added money ($moneyAmount) to ($walletOwnerName)";
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Transactions-Log',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'withdraw-Money',
                'old_obj'        => $walletObj,
            ]);


            event(new WithdrawMoneyFromWallet(
                $this->user_id,
                $walletId,
                $walletOwnerName,
                $currencySymbol,
                $moneyAmount,
                $notes,
                true,
                true
            ));

            return $this->returnMsgWithRedirection(
                $request,
                "admin/transactions-log/show-log/$walletOwnerName/$walletId",
                "the system is process your request now, once it is done we'll send you an email",
                true
            );

        }

        return $this->returnView($request, "admin.subviews.transactions_log.withdraw_money");


    }


}
