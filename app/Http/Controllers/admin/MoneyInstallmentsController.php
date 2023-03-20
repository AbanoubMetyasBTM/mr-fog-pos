<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\image;
use App\Events\MoneyInstallments\ReceiveMoneyInstallment;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\money_installments_m;
use App\models\wallets_m;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MoneyInstallmentsController extends AdminBaseController
{

    use CrudTrait;


    /** @var money_installments_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Money Installments");

        $this->modelClass          = money_installments_m::class;
        $this->viewSegment         = "money_installments";
        $this->routeSegment        = "money_installments";
        $this->primaryKey          = "id";
    }



    public function index(Request $request, $walletOwnerName, $walletId)
    {


        havePermissionOrRedirect("admin/money_installments", "show_schedule_money");

        $this->data["request_data"]      = (object)$request->all();
        $conds                           = $request->all();
        $this->data["results"]           = money_installments_m::getMoneyInstallmentsByWalletId($walletId, $conds);
        $this->data["wallet_owner_name"] = $walletOwnerName;
        $this->data["wallet_id"]         = $walletId;

        return $this->returnView($request, "admin.subviews.money_installments.show_schedule_money");

    }


    #region receive-money-installments

    public function receiveInstallment(Request $request, $walletOwnerName, $walletId, $installmentId)
    {
        havePermissionOrRedirect("admin/money_installments", "receive_money_installment");

        $checkStatus = $this->checkIfScheduledMoneyIsNotReceive($installmentId);
        if ($checkStatus == false) {
            return $this->returnMsgWithRedirection (
                $request,
                "money-installments/show-schedule-money/$walletOwnerName/$walletId",
                'This installment has already been paid'
            );
        }

        if ($request->method() == "POST") {

            if (isset($request['img_obj'])){

                // upload img file
                $imgObj  =image::general_save_img_without_attachment($request,[
                    "img_file_name" => "img_obj",
                ]);

                // update in db
                money_installments_m::updateMoneyInstallmentImg($installmentId,json_encode($imgObj));
            }

            createLog($request, [
                'item_id'        => $installmentId,
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'receive-Installment'
            ]);

            event(new ReceiveMoneyInstallment (
                $installmentId,
                $walletId
            ));


            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The installment received successfully",
                true
            );

        }

        $installmentObj                  = money_installments_m::findOrFail($installmentId);
        $this->data["item_data"]         = $installmentObj;
        $this->data["wallet_owner_name"] = $walletOwnerName;
        $this->data["wallet_id"]         = $walletId;


        return $this->returnView($request, "admin.subviews.money_installments.receive_money_installment");

    }
    #endregion


    #region validation-schedule-money
    public function scheduleAllMoneyValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["installments_num"]          = $request->get("installments_num");
        $rules_values["should_receive_payment_at"] = $request->get("should_receive_payment_at");


        $rules_itself["installments_num"]          = "required|numeric|min:1";
        $rules_itself["should_receive_payment_at"] = "required|date|after:today";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }

    public function addScheduleMoneyValidation(Request $request, $maxMoneyToSchedule)
    {
        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];


        $rules_values["money_amount"]              = $request->get("money_amount");
        $rules_values["should_receive_payment_at"] = $request->get("should_receive_payment_at");


        if ($rules_values["money_amount"] >  $maxMoneyToSchedule) {
            return [
                "error" => "Maximum amount to schedule " . $maxMoneyToSchedule
            ];
        }

        $rules_itself["money_amount"]              = "required|numeric|min:1";
        $rules_itself["should_receive_payment_at"] = "required|date|after:today";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);

    }
    #endregion

    #region schedule-debt-money
    public function scheduleAllDebtMoney(Request $request, $walletOwnerName, $walletId)
    {
        havePermissionOrRedirect("admin/money_installments", "schedule_all_debt_money");

        $walletObj                      = wallets_m::findOrFail($walletId);
        $scheduledMoneyObjs             = money_installments_m::getMoneyInstallmentsByWalletId
        (
            $walletId,
            ["is_received" => 0]
        );


        $moneyValue                     = $this->calculateScheduledAndNotScheduledMoney(
            $walletObj->wallet_amount,
            $scheduledMoneyObjs
        );


        if ($request->method() == "POST") {

            // check wallet is debt
            $wallet = $this->checkMoneyInWalletIsdebtOrOwed($walletId);
            if ($wallet != "debt") {
                return [
                    "error" => "This wallet does not have any debts, it cannot be scheduled as debt"
                ];
            }

            $validator = $this->scheduleAllMoneyValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            $data = $this->scheduleMoneyByInstallmentsNum (
                $request['installments_num'],
                $request['installments_type'],
                $request['should_receive_payment_at'],
                $moneyValue['not_scheduled_money'],
                $walletId,
                'debt'
            );

            money_installments_m::insert($data);


            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'schedule-All-Debt-Money'
            ]);


            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The amount has been scheduled successfully"
            );

        }


        $this->data["item_data"]           = $walletObj;
        $this->data["wallet_owner_name"]   = $walletOwnerName;
        $this->data['post_url']            = 'admin/money-installments/add-schedule-all-debt-money';
        $this->data['schedule_all_money']  = 'yes';
        $this->data['header_text']         = 'Schedule Debt All Money Of - ';
        $this->data['wallet_amount']       = $walletObj->wallet_amount;
        $this->data['scheduled_money']     = $moneyValue['scheduled_money'];
        $this->data['not_scheduled_money'] = $moneyValue['not_scheduled_money'];



        return $this->returnView($request, "admin.subviews.money_installments.schedule_money");

    }

    public function addScheduleDebtMoney(Request $request, $walletOwnerName, $walletId)
    {
        havePermissionOrRedirect("admin/money_installments", "add_schedule_debt_money");

        $walletObj                      = wallets_m::findOrFail($walletId);
        $scheduledMoneyObjs             = money_installments_m::getMoneyInstallmentsByWalletId
        (
            $walletId,
            ["is_received" => 0]
        );
        $moneyValue                     = $this->calculateScheduledAndNotScheduledMoney(
            $walletObj->wallet_amount,
            $scheduledMoneyObjs
        );


        if ($request->method() == "POST") {

            // check wallet is debt
            $wallet = $this->checkMoneyInWalletIsdebtOrOwed($walletId);
            if ($wallet != "debt") {
                return [
                    "error" => "This wallet does not have any debts, it cannot be scheduled as debt"
                ];
            }

            $validator = $this->addScheduleMoneyValidation($request, $moneyValue['not_scheduled_money']);
            if ($validator !== true) {
                return $validator;
            }


            $data = $this->scheduleMoneyByInstallmentsNum
            (
                1,
                'none',
                $request['should_receive_payment_at'],
                $request['money_amount'],
                $walletId,
                'debt'
            );

            money_installments_m::insert($data);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Schedule-Debt-Money'
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The amount has been scheduled successfully"
            );

        }

        $this->data["item_data"]           = $walletObj;
        $this->data["wallet_owner_name"]   = $walletOwnerName;
        $this->data['post_url']            = 'admin/money-installments/add-schedule-debt-money';
        $this->data['schedule_all_money']  = 'no';
        $this->data['header_text']         = 'Schedule Debt Money Of - ';
        $this->data['wallet_amount']       = $walletObj->wallet_amount;
        $this->data['scheduled_money']     = $moneyValue['scheduled_money'];
        $this->data['not_scheduled_money'] = $moneyValue['not_scheduled_money'];

        return $this->returnView($request, "admin.subviews.money_installments.schedule_money");

    }
    #endregion

    #region schedule-owed-money
    public function scheduleAllOwedMoney(Request $request, $walletOwnerName, $walletId)
    {

        havePermissionOrRedirect("admin/money_installments", "schedule_all_owed_money");

        $walletObj                     = wallets_m::findOrFail($walletId);
        $scheduledMoneyObjs            = money_installments_m::getMoneyInstallmentsByWalletId
        (
            $walletId,
            ["is_received" => 0]
        );
        $moneyValue                    = $this->calculateScheduledAndNotScheduledMoney(
            $walletObj->wallet_amount,
            $scheduledMoneyObjs
        );


        if ($request->method() == "POST") {

            // check wallet is owed
            $wallet = $this->checkMoneyInWalletIsdebtOrOwed($walletId);
            if ($wallet != "owed") {
                return [
                    "error" => "This wallet does not have any dues, it cannot be scheduled as owed"
                ];
            }

            $validator = $this->scheduleAllMoneyValidation($request);
            if ($validator !== true) {
                return $validator;
            }



            $data = $this->scheduleMoneyByInstallmentsNum
            (
                $request['installments_num'],
                $request['installments_type'],
                $request['should_receive_payment_at'],
                $moneyValue['not_scheduled_money'],
                $walletId,
                'owed'
            );


            money_installments_m::insert($data);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'schedule-All-Owed-Money'
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The amount has been scheduled successfully"
            );

        }

        $this->data["item_data"]           = $walletObj;
        $this->data["wallet_owner_name"]   = $walletOwnerName;
        $this->data['post_url']            = 'admin/money-installments/add-schedule-all-owed-money';
        $this->data['schedule_all_money']  = 'yes';
        $this->data['header_text']         = 'Schedule All Owed Money Of - ';
        $this->data['wallet_amount']       = $walletObj->wallet_amount;
        $this->data['scheduled_money']     = $moneyValue['scheduled_money'];
        $this->data['not_scheduled_money'] = $moneyValue['not_scheduled_money'];



        return $this->returnView($request, "admin.subviews.money_installments.schedule_money");

    }

    public function addScheduleOwedMoney(Request $request, $walletOwnerName, $walletId)
    {

        havePermissionOrRedirect("admin/money_installments", "add_schedule_owed_money");

        $walletObj                      = wallets_m::findOrFail($walletId);
        $scheduledMoneyObjs             = money_installments_m::getMoneyInstallmentsByWalletId
        (
            $walletId,
            ["is_received" => 0]

        );
        $moneyValue                     = $this->calculateScheduledAndNotScheduledMoney(
            $walletObj->wallet_amount,
            $scheduledMoneyObjs
        );


        if ($request->method() == "POST") {

            // check wallet is debt
            $wallet = $this->checkMoneyInWalletIsdebtOrOwed($walletId);
            if ($wallet != "owed") {
                return [
                    "error" => "This wallet does not have any dues, it cannot be scheduled as owed"
                ];
            }

            $validator = $this->addScheduleMoneyValidation($request, $moneyValue['not_scheduled_money']);
            if ($validator !== true) {
                return $validator;
            }


            $data = $this->scheduleMoneyByInstallmentsNum
            (
                1,
                'none',
                $request['should_receive_payment_at'],
                $request['money_amount'],
                $walletId,
                'owed'
            );

            money_installments_m::insert($data);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Schedule-Owed-Money'
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The amount has been scheduled successfully"
            );

        }

        $this->data["item_data"]           = $walletObj;
        $this->data["wallet_owner_name"]   = $walletOwnerName;
        $this->data['post_url']            = 'admin/money-installments/add-schedule-owed-money';
        $this->data['schedule_all_money']  = 'no';
        $this->data['header_text']         = 'Schedule Owed Money Of - ';
        $this->data['wallet_amount']       = $walletObj->wallet_amount;
        $this->data['scheduled_money']     = $moneyValue['scheduled_money'];
        $this->data['not_scheduled_money'] = $moneyValue['not_scheduled_money'];


        return $this->returnView($request, "admin.subviews.money_installments.schedule_money");

    }
    #endregion

    #region delete-schedule-money
    public function beforeDeleteRow($itemId)
    {

        havePermissionOrRedirect("admin/money_installments", "delete_schedule_money");

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["item_id"] = $itemId;
        $rules_itself["item_id"] = "required|numeric|exists:money_installments,id";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);
    }

    public function delete(Request $request)
    {

        $validator = $this->beforeDeleteRow($request['item_id']);
        if ($validator !== true) {
            return $validator;
        }


        $checkStatus = $this->checkIfScheduledMoneyIsNotReceive($request['item_id']);
        if ($checkStatus == false){
            $output["msg"] = "can not delete this Installment because it has been paid";
            return json_encode($output);
        }

        $this->general_remove_item($request, $this->modelClass);
    }
    #endregion


    #region edit-schedule-money
    public function beforeEdit(Request $request)
    {
        havePermissionOrRedirect("admin/money_installments", "delete_schedule_money");

        $rules_values = [];
        $rules_itself = [];
        $attrs_names  = [];

        $rules_values["should_receive_payment_at"] = $request->get('should_receive_payment_at');
        $rules_itself["should_receive_payment_at"] = "required|date|after:today";

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);
    }

    public function edit(Request $request, $walletOwnerName, $walletId, $installmentId)
    {

        havePermissionOrRedirect("admin/money_installments", "edit_schedule_money");


        $installmentObj                  = money_installments_m::findOrFail($installmentId);
        $this->data["item_data"]         = $installmentObj;
        $this->data["wallet_owner_name"] = $walletOwnerName;
        $this->data["wallet_id"]         = $walletId;

        $checkStatus = $this->checkIfScheduledMoneyIsNotReceive($installmentId);
        if ($checkStatus == false){
            return $this->returnMsgWithRedirection
            (
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                'can not edit this Installment because it has been paid'
            );
        }

        if ($request->method() == "POST") {

            $validator = $this->beforeEdit($request);
            if ($validator !== true) {
                return $validator;
            }

            $date = Carbon::parse($request['should_receive_payment_at'])->format('Y-m-d');
            // update
            money_installments_m::updateMoneyInstallmentDate($installmentId, $date);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Money-Installments',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'edit',
                'old_obj'        => $installmentObj,
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/money-installments/show-schedule-money/$walletOwnerName/$walletId",
                "The installment date updated successfully"
            );

        }



        return $this->returnView($request, "admin.subviews.money_installments.edit_schedule_money");

    }
    #endregion



    private function scheduleMoneyByInstallmentsNum
    (
        $installmentsNum,
        $installmentsType,
        $shouldReceivePaymentAt,
        $moneyAmount,
        $walletId,
        $moneyType
    )
    {
        // $installmentsType => three_month, two_month, one_month, two_week, week, none
        // $moneyType => owed, debt

        $newMoneyAmount = $moneyAmount / $installmentsNum;

        $newMoneyAmount = number_format($newMoneyAmount,2);
        $data = [];
        for ($i = 0; $i < $installmentsNum; $i++){

            $data[$i]['wallet_id']                 = $walletId;
            $data[$i]['money_type']                = $moneyType;
            $data[$i]['money_amount']              = $newMoneyAmount;
            $data[$i]['should_receive_payment_at'] = Carbon::parse($shouldReceivePaymentAt)->format('Y-m-d');
            $data[$i]['is_received']               = 0;
            $shouldReceivePaymentAt                = $this->calculateshouldReceivePaymentAtByInstallmentsType(
                $installmentsType,
                $shouldReceivePaymentAt
            );
            $data[$i]['created_at']                = now();
            $data[$i]['updated_at']                = now();

        }
        return $data;
    }

    private function checkMoneyInWalletIsDebtOrOwed($walletId)
    {
        $walletObj = wallets_m::findOrFail($walletId);


        if ($walletObj->wallet_amount > 0){
            return 'debt';

        }
        elseif($walletObj->wallet_amount < 0){
            return 'owed';
        }
        else{
            return 0;
        }

    }


    private function calculateScheduledAndNotScheduledMoney($walletValue, $notReceivedScheduledMoneyObjs)
    {


        $scheduledMoneyValue = 0;
        foreach ($notReceivedScheduledMoneyObjs as $scheduledMoneyObj){

            $scheduledMoneyValue = $scheduledMoneyValue + $scheduledMoneyObj['money_amount'];
        }


        $walletValue = abs($walletValue);

        $notScheduledMoneyValue = $walletValue - $scheduledMoneyValue;

        return ['scheduled_money' => $scheduledMoneyValue, 'not_scheduled_money' => $notScheduledMoneyValue] ;

    }


    private function calculateShouldReceivePaymentAtByInstallmentsType($installmentsType, $startTime)
    {
        $startTime = Carbon::parse($startTime);
        $installmentsTypes= [

            'three_month' => $startTime->clone()->addMonths(3),
            'two_month'   => $startTime->clone()->addMonths(2),
            'one_month'   => $startTime->clone()->addMonth(),
            'two_week'    => $startTime->clone()->addWeeks(2),
            'one_week'    => $startTime->clone()->addWeek(),
            'none'        => $startTime->clone()
        ];

        $date = $installmentsTypes[$installmentsType]->format('Y-m-d');

        return $date;
    }


    private function checkIfScheduledMoneyIsNotReceive($installmentId)
    {
        $scheduleMoneyRow = money_installments_m::findOrFail($installmentId);

        if (is_object($scheduleMoneyRow)){

            if ($scheduleMoneyRow->is_received == 0){

                return true;
            }

            return false;

        }

    }
}
