<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\BTMAdminValidator;
use App\btm_form_helpers\general_save_form;
use App\Events\Wallets\DepositMoneyForWallet;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\form_builder\ExpensesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\transactions_log_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class ExpensesController extends AdminBaseController
{

    use CrudTrait;

    /** @var transactions_log_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Expenses");

        $this->modelClass   = transactions_log_m::class;
        $this->viewSegment  = "expenses";
        $this->routeSegment = "expenses";
        $this->builderObj   = new ExpensesBuilder();
        $this->primaryKey   = "t_log_id";
    }

    public function save(Request $request,$item_id = null)
    {

        $this->beforeDoAnythingAtSave($request,$item_id);

        if($this->editOnly && $item_id == null){
            return abort(404);
        }

        $item_obj   = "";

        $this->getAllLangs();

        $item_obj = $this->appendDataAtEditOperationForView($request, $item_obj);

        $this->data["item_data"]  = $item_obj;
        $this->data["builderObj"] = $this->builderObj;

        if ($request->method()=="POST")
        {
            $validation_msgs = BTMAdminValidator::checkValidation($request,$this->builderObj,$this->data["all_langs"]);
            if($validation_msgs!==true){
                return $validation_msgs;
            }

            $validation_msgs = $this->customValidation($request,$item_id);
            if($validation_msgs!==true){
                return $validation_msgs;
            }

            $request  = $this->beforeSaveRow($request);

            $request=general_save_form::prepare_fields_before_save(
                $request,
                $this->builderObj,
                $this->data["all_langs"],
                $item_obj
            );

            $request = $this->beforeAddNewRow($request);

            createLog($request, [
                'item_id'        => $item_id,
                'user_id'        => $this->user_id,
                'module'         => "Expenses",
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
            ]);

            $item_obj->wallet_id = $request->get('wallet_id');

            return $this->afterSaveRedirectionOptions($request, $item_obj);
        }

        return $this->returnView($request,(empty($this->fullViewPath)?"admin.subviews.$this->viewSegment.save":$this->fullViewPath));
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/expenses", $item_id == null ? "add_action" : "edit_action");

        if ($item_id > 0) {

            return $this->returnMsgWithRedirection(
                $request,
                "admin/branches",
                "you can not edit"
            );


        }

        $this->data["item_data"] = (object)$request->all();

    }

    public function appendDataAtEditOperationForView(Request $request, $item_obj)
    {

        if ($item_obj == "") {
            $item_obj            = new \stdClass();
            $item_obj->branch_id = $request->get("branch_id");
        }

        return $item_obj;

    }

    public function customValidation(Request $request)
    {

        if ($this->branch_id!=null && $this->branch_id!=$request->get("branch_id")){
            return [
                "error" => "invalid branch"
            ];
        }

        if (isset($request["transaction_amount"])) {

            $branchAndWallet = $this->getBranchAndWallet($request);
            $wallet          = $branchAndWallet["walletObj"];

            if (floatval($wallet->wallet_amount) < floatval($request["transaction_amount"])) {
                return [
                    "error" => "The amount in the wallet is not enough"
                ];
            }
        }
        return true;
    }

    public function beforeAddNewRow(Request $request)
    {

        $branchAndWallet = $this->getBranchAndWallet($request);
        $wallet          = $branchAndWallet["walletObj"];
        $branch          = $branchAndWallet["branchObj"];

        event(new WithdrawMoneyFromWallet(
            $this->user_id,
            $wallet->wallet_id,
            $branch->branch_name,
            $branch->branch_currency,
            $request->get('transaction_amount'),
            $request->get('transaction_notes'),
            true,
            false,
            'expenses',
            $request->get('money_type')
        ));

        $request['wallet_id'] = $wallet->wallet_id;

        return $request;

    }


    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {

        $branchObj = branches_m::getBranchWalletsId($request->get('wallet_id'));

        return $this->returnMsgWithRedirection(
            $request,
            "admin/transactions-log/show-log/$branchObj->branch_name/
                $branchObj->cash_wallet_id,
                $branchObj->debit_card_wallet_id,
                $branchObj->credit_card_wallet_id,
                $branchObj->cheque_wallet_id
            ",
        'Saved Successfully',
        true
        );
    }

    public function validationBeforeRefund(Request $request, $item_id)
    {
        $request['item_id'] = $item_id;
        $validator          = \Validator::make($request->all(), [
            'item_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return false;
        }

        return true;

    }

    public function refund(Request $request, $item_id)
    {
        $transactionLogObj = transactions_log_m::getTransactionLogById($item_id);
        $branchObj         = branches_m::getBranchWalletsId($transactionLogObj->wallet_id);

        if (!is_object($transactionLogObj)) {
            return abort(404);
        }


        $redirectLink = "admin/transactions-log/show-log/$branchObj->branch_name/
                    $branchObj->cash_wallet_id,
                    $branchObj->debit_card_wallet_id,
                    $branchObj->credit_card_wallet_id,
                    $branchObj->cheque_wallet_id";


        if (
            $this->validationBeforeRefund($request, $transactionLogObj->t_log_id) == false ||
            $transactionLogObj->transaction_operation == 'increase'
        ) {
            return $this->returnMsgWithRedirection(
                $request,
                $redirectLink,
                "transaction log id is incorrect"
            );
        }

        if ($transactionLogObj->is_refunded) {
            return $this->returnMsgWithRedirection(
                $request,
                $redirectLink,
                "transaction is refunded already"
            );
        }

        if ($request->method() != "POST") {

            $this->data["item_id"]                   = $transactionLogObj->t_log_id;
            $this->data["show_transaction_log_link"] = $redirectLink;

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Expenses',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'refund',
                'old_obj'        => $transactionLogObj,
            ]);

            return $this->returnView($request, "admin.subviews.$this->viewSegment.refund");
        }


        $this->saveRefundedRow($request,$transactionLogObj);


        return $this->returnMsgWithRedirection(
            $request,
            $redirectLink,
            "Saved Successfully",
            true
        );

    }

    private function saveRefundedRow(Request $request, $transactionLogObj)
    {

        $additional_mgs   = "This Refund belong to transaction log number ({$transactionLogObj->t_log_id}), Notes => ";
        $transactionNotes = $additional_mgs . $request['transaction_notes'];

        $branchObj = branches_m::getBranchWalletsId($transactionLogObj->wallet_id);

        createLog($request, [
            'user_id'        => $this->user_id,
            'module'         => 'Expenses',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'save-Refunded-Row',
            'old_obj'        => $transactionLogObj,
        ]);

        event(new DepositMoneyForWallet(
            $this->user_id,
            $transactionLogObj->wallet_id,
            $branchObj->branch_name,
            $transactionLogObj->transaction_currency,
            $transactionLogObj->transaction_amount,
            $transactionNotes,
            false,
            $transactionLogObj->transaction_type,
            $transactionLogObj->money_type
        ));

        transactions_log_m::updateRow($transactionLogObj->t_log_id,[
            "is_refunded" => "1"
        ]);


    }

    private function getBranchAndWallet(Request $request)
    {

        $branch = branches_m::getBranchById($request->branch_id);

        if (!is_object($branch)) {
            abort(404);
            die("");
        }

        $branch_wallet_type = $request["money_type"] . '_wallet_id';

        return [
            "branchObj" => $branch,
            "walletObj" => wallets_m::getWalletById($branch->{$branch_wallet_type}),
        ];

    }

}
