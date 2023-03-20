<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\RegisterSessionLogTrait;
use App\models\register\registers_m;
use App\models\register\registers_sessions_logs_m;
use App\models\register\registers_sessions_m;
use Illuminate\Http\Request;


class RegisterSessionsController extends AdminBaseController
{

    use RegisterSessionLogTrait;

    public function __construct()
    {
        parent::__construct();
    }



    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/registers_sessions", "show_action");

        $this->data["registers"]     = registers_m::getAllRegisterOrSpecificBranch();
        $this->data["request_data"]  = (object)$request->all();
        $conds                       = $request->all();
        $conds['paginate']           = 50;
        $this->data["results"]       = registers_sessions_m::getAllRegisterSessionsOrSpecificBranchRegisterSessions($conds);
        return $this->returnView($request, "admin.subviews.register_sessions.show");
    }


    private function addChangeAmountValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];

        $rules_values["change_amount"] = $request->get("change_amount");
        $rules_itself["change_amount"] = "required|numeric|min:1";


        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);
    }



    public function addChangeAmount(Request $request, $registerId)
    {
        havePermissionOrRedirect("admin/registers_sessions", "add_change");

        // check if register session not null
        if (is_null($request->session()->get('register_session_id')))
        {
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'This session has an error, change cannot be added'
            );
        }


        // check if register follow specific branch
        $this->checkIfRegisterFollowSpecificBranch($registerId);


        // check is set session running on register
        if ($this->checkIfIsSetRegisterSessionOnCurrentTime($request, $registerId) === false){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'There is no session on this register to end'
            );
        }


        // check if who start Who will add change amount
        if (!empty($this->checkIfUserWhoStartsSessionWillEndIt($request, $registerId))){
            return $this->checkIfUserWhoStartsSessionWillEndIt($request, $registerId);
        }

        $this->data["register_id"] = $registerId;

        if ($request->method() == "POST") {

            $validator = $this->addChangeAmountValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => "Register-Sessions",
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Change-Amount'
            ]);

            // create register session log
            $this->createRegisterSession(
                $request->session()->get('register_session_id'),
                0,
                'change',
                'increase',
                $request->get("change_amount"),
                0,
                0,
                0
            );

            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'Session started successfully'
            );
        }

        return $this->returnView($request, "admin.subviews.register_sessions.add_change");


    }

    public function startRegisterSession(Request $request, $registerId)
    {

        havePermissionOrRedirect("admin/registers_sessions", "start_session");

        // check if register follow specific branch
        $this->checkIfRegisterFollowSpecificBranch($registerId);


        $employeeId = $request->session()->get('this_user_id');
        // check if employee not open another session
        if ($this->checkIfEmployeeHasNotEndedSession($employeeId) === true){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'Can not open more one session in the same time'
            );
        }


        // can not start session on register in the same time
        if ($this->checkIfIsSetRegisterSessionOnCurrentTime($request, $registerId) === true){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'There is a session working on this register at the current time'
            );
        }

        $this->data["register_id"] = $registerId;

        if ($request->method() == "POST") {

            $data['register_id']                = $registerId;
            $data['employee_id']                = $request->session()->get('this_user_id');
            $data['register_start_cash_amount'] = floatval($request->get('register_start_cash_amount'));
            $data['register_start_at']          = now();
            $regSessionObj                       = registers_sessions_m::createRegisterSession($data);

            $request->session()->put('register_id', $registerId);
            $request->session()->put('register_session_id', $regSessionObj->id);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => "Register-Sessions",
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'start-Register-Session'
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'Session started successfully'
            );
        }

        return $this->returnView($request, "admin.subviews.register_sessions.start_session");
    }

    public function endRegisterSession(Request $request, $registerId)
    {

        havePermissionOrRedirect("admin/registers_sessions", "end_session");

        // check if register follow specific branch
        $this->checkIfRegisterFollowSpecificBranch($registerId);


        // check is set session running on register
        if ($this->checkIfIsSetRegisterSessionOnCurrentTime($request, $registerId) === false){

            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'There is no session on this register to end'
            );
        }


        // check if who start who will end session
        if (!empty($this->checkIfUserWhoStartsSessionWillEndIt($request, $registerId))){
            return $this->checkIfUserWhoStartsSessionWillEndIt($request, $registerId);
        }

        $this->data["register_id"] = $registerId;
        $this->data["data"]        = $this->calculateExpectedEndAmountOfRegisterSession($registerId);


        if ($request->method() == "POST") {
            $reqSessionObj    = registers_sessions_m::getNotEndedRegisterSessionByRegisterId($registerId);
            $data             = $this->calculateExpectedEndAmountOfRegisterSession($registerId);
            $data['register_closed_at'] = now();

            registers_sessions_m::updateRegisterSession($reqSessionObj->id, $data);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => "Register-Sessions",
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'end-Register-Session',
                'old_obj'        => $reqSessionObj,
            ]);

            // delete register_session_id from session
            $request->session()->forget('register_session_id');

            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'Session ended successfully'
            );

        }

        return $this->returnView($request, "admin.subviews.register_sessions.end_session");
    }

    private function checkIfIsSetRegisterSessionOnCurrentTime(Request $request, $registerId)
    {
        $regSession = registers_sessions_m::getNotEndedRegisterSessionByRegisterId($registerId);

        if (is_object($regSession)){
            return true;
        }
        return false;
    }

    private function checkIfEmployeeHasNotEndedSession($employeeId)
    {
        $regSession = registers_sessions_m::getNotEndedRegisterSessionByEmployeeId($employeeId);

        if (is_object($regSession)){
            return true;
        }
        return false;
    }


    private function checkIfRegisterFollowSpecificBranch($registerId)
    {
        $registerObj = registers_m::findOrFail($registerId);
        if ($this->branch_id!=null && $registerObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }

    }

    private function checkIfUserWhoStartsSessionWillEndIt(Request $request, $registerId)
    {
        $regSession = registers_sessions_m::getNotEndedRegisterSessionByRegisterId($registerId);

        if ($regSession->employee_id != $request->session()->get('this_user_id')){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'You are not the user who started the session'
            );
        }

    }

    private function calculateExpectedEndAmountOfRegisterSession($registerId)
    {
        $regSessionObj       = registers_sessions_m::getNotEndedregisterSessionByRegisterId($registerId);
        $regSessionItems     = collect(registers_sessions_logs_m::getLogsByRegisterSessionId($regSessionObj->id));

        // calculate end amount
        $data['register_end_cash_amount'] = $this->calculateEndAmountBasedOnAmountType(
            'cash_paid_amount',
            $regSessionObj->register_start_cash_amount,
            $regSessionItems
        );
        $data['register_end_debit_amount'] = $this->calculateEndAmountBasedOnAmountType(
            'debit_card_paid_amount',
            0,
            $regSessionItems
        );
        $data['register_end_credit_amount'] = $this->calculateEndAmountBasedOnAmountType(
            'credit_card_paid_amount',
            0,
            $regSessionItems
        );
        $data['register_end_cheque_amount'] = $this->calculateEndAmountBasedOnAmountType(
            'cheque_paid_amount',
            0,
            $regSessionItems
        );

        // count End receipts
        $data['register_end_debit_count'] = $this->countEndReceiptsNumBasedOnAmountType(
            'debit_card_paid_amount',
            $regSessionItems
        );
        $data['register_end_credit_count']  = $this->countEndReceiptsNumBasedOnAmountType(
            'credit_card_paid_amount',
            $regSessionItems
        );
        $data['register_end_cheque_count']  = $this->countEndReceiptsNumBasedOnAmountType(
            'cheque_paid_amount',
            $regSessionItems
        );

       return $data;
    }

    private function calculateEndAmountBasedOnAmountType($amountType, $startAmount, $regSessLogItems)
    {
        // $amountType => cash, debit_card, credit_card, cheque

        $data  = $startAmount + $regSessLogItems->where('operation_type','=', 'increase')->where("$amountType", '!=', 0)->sum("$amountType");
        $data -= $regSessLogItems->where('operation_type','=', 'decrease')->where("$amountType",'!=', 0)->sum("$amountType");

        return $data;
    }

    private function countEndReceiptsNumBasedOnAmountType($amountType, $regSessLogItems)
    {
        // $amountType => debit_card, credit_card, cheque
        $receipts = $regSessLogItems->where("$amountType",'!=', 0)->all();
        return count($receipts);
    }

}
