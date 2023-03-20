<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\BTMAdminValidator;
use App\btm_form_helpers\form;
use App\btm_form_helpers\general_save_form;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Http\Controllers\AdminBaseController;

use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_paycheck_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class PaychecksController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->setMetaTitle("Employee Paycheck");
    }

    private function addPaycheckValidation(Request $request, $employeeId, $notPaidWeeksIndexes, $year, $month)
    {
        $employeeObj = employee_details_m::getEmployeeDataByEmployeeId($employeeId);

        if (!is_object($employeeObj) || $employeeObj->branch_id != $this->branch_id || empty($year) || empty($month)){
           abort(404);
           die();
        }


        $notPaidWeeksIndexes = array_map('intval', explode(',', $notPaidWeeksIndexes));
        $paidWeeks           = hr_paycheck_m::getPaychecksOfEmployeeByYearAndMonth($employeeId, $year, $month)->pluck('p_weeks');
        $allPaidWeeksIndexes = [];

        foreach ($paidWeeks as $week){
            $allPaidWeeksIndexes = array_merge($allPaidWeeksIndexes, explode(',', $week));
        }



        if (!empty(array_intersect($allPaidWeeksIndexes, $notPaidWeeksIndexes))){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/dashboard',
                'The salary has already been paid'
            );
        }


        $workingWeeksIndexes = array_keys($this->getEmpWorkingWeeks($employeeId, $request->get('year'), $request->get('month')));


        if (empty($workingWeeksIndexes) || count(array_diff($notPaidWeeksIndexes, $workingWeeksIndexes)) > 0){

            return $this->returnMsgWithRedirection(
                $request,
            'admin/dashboard',
                'This weeks not valid'
            );

        }


        if (empty(array_intersect($workingWeeksIndexes, $notPaidWeeksIndexes))){

            return $this->returnMsgWithRedirection(
                $request,
                'admin/dashboard',
                'Week Index is not valid'
            );
        }


    }


    public function addPaycheck(Request $request, $empId, $notPaidWeeksIndexes)
    {

        havePermissionOrRedirect("admin/paycheck", "add_action");



        $this->addPaycheckValidation($request, $empId, $notPaidWeeksIndexes, $request->get('year'), $request->get('month'));

        $notPaidWeeksIndexes = explode(',', $notPaidWeeksIndexes);
        $workingMonthWeeks   = $this->getEmpWorkingWeeks($empId, $request->get('year'), $request->get('month'));
        $data                = $this->calculateWorkTimeBasedOnWeeksIndexes($notPaidWeeksIndexes, $empId, $workingMonthWeeks);

        $this->data['total_work_hours']        = $data['total_work_hours'] . ":00";
        $this->data['total_price']             = $data['total_price'];
        $this->data['header_text']             = 'Employees Paychecks';
        $this->data['employee_id']             = $empId;
        $this->data['month']                   = $request->get('month');
        $this->data['year']                    = $request->get('year');
        $this->data['not_paid_weeks_indexes'] = implode(',', $notPaidWeeksIndexes);


        if ($request->method() == "POST") {

            $this->beforeAddPaycheckValidation($request);

            $empObj             = employee_details_m::getEmployeeDataByEmployeeId($empId);
            $branch             = branches_m::getBranchById($this->branch_id);
            $branch_wallet_type = $request["money_type"] . '_wallet_id';
            $wallet             = wallets_m::getWalletById($branch->{$branch_wallet_type});

            $note = "employee $empObj->full_name was received a salary => ". $data['total_price'] .
                    " according to the number of working hours => ". $data['total_work_hours']. " in a month ( "
                    . $request->get('month'). " ) a year ( " . $request->get('year') . " ) a week ( "
                    . implode(',', $notPaidWeeksIndexes) . " )";


            event(new WithdrawMoneyFromWallet(
                $this->user_id,
                $wallet->wallet_id,
                $branch->branch_name,
                $branch->branch_currency,
                $data['total_price'],
                $note,
                true,
                false,
                'paycheck',
                $request->get('money_type')
            ));


            $payCheckData['employee_id']          = $empId;
            $payCheckData['p_year']               = $request->get('year') ;
            $payCheckData['p_month']              = $request->get('month');
            $payCheckData['p_weeks']              = implode(',', $notPaidWeeksIndexes);
            $payCheckData['p_should_work_hours']  = $data['days_should_work_hours'];
            $payCheckData['p_total_worked_hours'] = $data['total_work_hours'] . ":00";
            $payCheckData['p_amount']             = $data['total_price'];
            $payCheckData['p_is_received']        = $request->get('is_received');

            hr_paycheck_m::addPaycheck($payCheckData);

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'paychecks',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Paycheck',
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-login-logout/employee/$empId",
                "Salary has been successfully paid"
            );

        }

        return $this->returnView($request, "admin.subviews.paychecks.save");

    }


    private function beforeAddPaycheckValidation(Request $request)
    {
        $moneyTypes = [
            "cash",
            "debit_card",
            "credit_card",
            "cheque"
        ];

        $isReceivedArr = [
            0,
            1
        ];

        if (!in_array($request->get("money_type"), $moneyTypes)) {
            return $this->returnErrorMessages(
                $request,
                'Money type not valid'
            );
        }

        if (!in_array($request->get('is_received'), $isReceivedArr)) {
            return $this->returnErrorMessages(
                $request,
                'Money is received not valid'
            );
        }

    }


    private function calculateWorkTimeBasedOnWeeksIndexes(array $weeksNotPaidIndexes, $employeeId, $workingMonthWeeks):array
    {
        $allNotPaidDays = [];
        foreach ($weeksNotPaidIndexes as $index){
            $allNotPaidDays = array_merge($allNotPaidDays, $workingMonthWeeks[$index]);
        }

        $empObj              = employee_details_m::getEmployeeDataByEmployeeId($employeeId);
        $allNotPaidDays      = collect($allNotPaidDays);
        $daysWorkHours       = $allNotPaidDays->pluck('working_hours')->toArray();
        $daysOverTimeHours   = $allNotPaidDays->pluck('overtime_hours')->toArray();
        $daysShouldWorkHours = $allNotPaidDays->pluck('should_work_hours')->toArray();

        $totalWorkHours  = explode(':', calculateTotalWorkHoursAfterOvertimeHours(
                                            $daysWorkHours,
                                            $daysOverTimeHours,
                                            $empObj->employee_overtime_hour_rate));

        $totalPrice  =
            ( intval($totalWorkHours[0]) * $empObj->hour_price ) +
            ( ( intval($totalWorkHours[1]) / 60 ) * $empObj->hour_price );



        $daysShouldWorkHours = calculateTotalTime($daysShouldWorkHours, 1);


        return [
            'total_work_hours'       => implode(':', $totalWorkHours),
            'total_price'            => round($totalPrice, 2),
            'days_should_work_hours' => $daysShouldWorkHours
        ];
    }

    private function getEmpWorkingWeeks($employeeId, $year, $month):array
    {

        $empObj       = employee_details_m::getEmployeeDataByEmployeeId($employeeId);
        $branchObj    = branches_m::getBranchById($empObj->branch_id);
        $date         = $year . '-' . $month;
        $dateFrom     = date('Y-m-01', strtotime($date));
        $dateTo       = date('Y-m-t', strtotime($date));
        $allMonthDays = employee_login_logout_m::getLoginLogoutTblOfByEmployeeIdAndSpecificTime($employeeId, $dateFrom, $dateTo);
        $data         = [];
        $result       = [];
        $index        = 0;


        for ($day = $dateFrom; $day <= $dateTo; $day++) {
            $item = $allMonthDays->where('work_date', '=', $day)->first();
            if (is_null($item)){
                continue;
            }

            if (strtolower(date('l', strtotime($day))) == strtolower($branchObj->first_day_of_the_week) && $day != $dateFrom){
                $index++;
                $result[$index] = $data[$index-1];
            }

            $data[$index][] = $item;

            if ($day == $dateTo ){
                $index++;
                $result[$index] = $data[$index-1];
            }

        }

        return $result;
    }

    public function changePaycheckIsReceived(Request $request)
    {

        havePermissionOrRedirect("admin/paycheck", "change_is_received");



        if ($request['accept'] == '0') {
            echo json_encode([
                "error" => "you can not edit this"
            ]);
        }

        // update paycheck is_received
        hr_paycheck_m::updatePaycheckIsReceived($request->get("item_id"), $request['accept']);


        createLog($request, [
            'user_id'        => $this->user_id,
            'module'         => 'paychecks',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'change-Paycheck-Is-Received'
        ]);

        $isReceivedStatus = [
            "1" => "Yes",
            "0" => "No"
        ];

        return json_encode([
            "msg" => capitalize_string($isReceivedStatus[$request['accept']])
        ]);

    }


}
