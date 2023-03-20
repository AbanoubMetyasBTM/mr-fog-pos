<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_delay_early_requests_m;
use App\models\hr\hr_holiday_requests_m;
use App\models\hr\hr_national_holidays_m;
use App\models\hr\hr_paycheck_m;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class EmployeeLoginLogoutController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->setMetaTitle("Employee Login Logout");
    }


    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/my_hr_employee_login_logout", "show_action");
        $employeeId                     = $this->user_id;
        $this->data["header"]           = 'My Check In Check Out Table';
        $this->data["add_login_logout"] = true;
        $employeeObj                    = employee_details_m::getEmployeeDataByEmployeeId($employeeId);

        if (!is_object($employeeObj)){
            abort(404);
        }

        $this->data["current_day_login_logout"] = employee_login_logout_m::getCurrentDayLoginLogoutTblByEmployeeId($employeeId);
        $this->createNotCreatedDaysOnCurrentMonth($employeeObj);

        $conds = $request->all();

        if (isset($conds['month']) && !empty($conds['month']) && isset($conds['year']) && !empty($conds['year'])){

            $conds['date']           = $conds['year'] . "-" . $conds['month'];
            $this->data["date_from"] = date('Y-m-01', strtotime($conds['date']));
            $this->data["date_to"]   = date('Y-m-t', strtotime($conds['date']));
            $this->data["year"]      = $conds['year'];
            $this->data["month"]     = $conds['month'];

            if ($conds['date'] != date('Y-m')){
                $this->data["add_login_logout"] = false;
            }

        }
        else {
            $this->data["date_from"] = date('Y-m-01');
            $this->data["date_to"]   = date('Y-m-t');
            $this->data["year"]      = date('Y');
            $this->data["month"]     = date('m');
        }

        $this->data["employee_data"]           = $employeeObj;
        $this->data["branch_data"]             = branches_m::getBranchById($this->branch_id);
        $this->data["results"]                 = employee_login_logout_m::getLoginLogoutTblOfCurrentMonthByEmployeeId($employeeId, $conds);
        $this->data["total_should_work_hours"] = calculateTotalTime($this->data["results"]->pluck('should_work_hours')->all());
        $this->data["total_working_hours"]     = calculateTotalWorkHoursAfterOvertimeHours(
            $this->data["results"]->pluck('working_hours')->all(),
            $this->data["results"]->pluck('overtime_hours')->all(),
            $employeeObj->employee_overtime_hour_rate
        );


        $this->data['paychecks'] = hr_paycheck_m::getPaychecksOfEmployeeByYearAndMonth($employeeId,  $this->data["year"] , $this->data["month"]);

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.employee_login_logout.show");
    }

    public function loginEmployeeWork(Request $request)
    {
        $employeeObj           = employee_details_m::getEmployeeDataByEmployeeId($this->user_id);
        $currentDayLoginLogout = employee_login_logout_m::getCurrentDayLoginLogoutTblByEmployeeId($employeeObj->employee_id);

        $newLoginTime[] = [
            'login' => now()->format('H:i:s'),
        ];

        if (!empty($currentDayLoginLogout->login_logout_times)){
            $oldLoginTimes          = json_decode($currentDayLoginLogout->login_logout_times, true);
            $theLastLoginLogoutTime = $oldLoginTimes[array_key_last($oldLoginTimes)];

            // check if last login-logout ended or not
            if (!isset($theLastLoginLogoutTime['logout'])){
                abort(404);
            }

            $data['login_logout_times'] = array_merge($oldLoginTimes, $newLoginTime);
        }
        else {
            // first login
            // check if employee is late

            if ( $this->checkIfEmployeeIsLateWhenLogin($employeeObj) === true){
                $data['late_time_hours'] = $this->calculateLateTimeHours($employeeObj, null, $currentDayLoginLogout);
            }

            // update login logout times
            $data['login_logout_times'] = json_encode($newLoginTime);
        }

        employee_login_logout_m::updateLoginLogoutData($currentDayLoginLogout->id, $data);

        return $this->returnMsgWithRedirection(
            $request,
            url('admin/employee-hr/employee-login-logout'),
            'Logged in successfully'
        );


    }

    public function logoutEmployeeWork(Request $request)
    {
        $currentDayLoginLogout     = employee_login_logout_m::getCurrentDayLoginLogoutTblByEmployeeId($this->user_id);
        $employeeObj               = employee_details_m::getEmployeeDataByEmployeeId($this->user_id);
        $loginLogoutTimes          = json_decode($currentDayLoginLogout->login_logout_times, true);
        $theLastLoginLogoutTimeKey = array_key_last($loginLogoutTimes);

        // check if last login-logout ended or not
        if (isset($theLastLoginLogoutTime['logout'])){
            abort(404);
        }


        $loginLogoutTimes[$theLastLoginLogoutTimeKey]['logout'] = now()->format('H:i:s');

        $result = $this->calculateHoursWhenEmployeeClickLogout($currentDayLoginLogout, $loginLogoutTimes);


        $data['login_logout_times'] = $loginLogoutTimes;
        $data['working_hours']      = $result['total_working_hours'];
        $data['remain_hours']       = $result['remain_hours'];
        $data['overtime_hours']     = $result['overtime_hours'];

        if ($this->checkIfEmployeeIsEarlyLeaveWhenLogout($employeeObj) === true){
            $data['early_leave_hours'] = $this->calculateEarlyLeaveTimeHours($employeeObj);
        }

        // update login logout times
        employee_login_logout_m::updateLoginLogoutData($currentDayLoginLogout->id, $data);


        return $this->returnMsgWithRedirection(
            $request,
            url('admin/employee-hr/employee-login-logout'),
            'Logged out successfully'
        );


    }

    private function returnWillSaveDataForEditLoginLogout(Request $request, $loginLogoutObj){

        $empObj             = employee_details_m::getEmployeeDataByEmployeeId($loginLogoutObj->employee_id);
        $loginLogoutTimes   = json_decode($loginLogoutObj->login_logout_times, true);
        $newValue           = $request->get('value');
        $newValueTimeStamps = strtotime($newValue);
        $timeIndex          = $request->get('time_index');
        $timeType           = $request->get('time_type');


        if (empty($loginLogoutTimes)) {
            // update login time only
            $data['login_logout_times'][] = [
                'login' => date('H:i:s', $newValueTimeStamps)
            ];

            $data['login_logout_times'] = json_encode($data['login_logout_times']);
            $data['late_time_hours']    = $this->calculateLateTimeHours($empObj, $request->get('value'), $loginLogoutObj);

            return $data;
        }

        $loginLogoutTimes[$timeIndex][$timeType] = date('H:i:s' , $newValueTimeStamps);

        if(
            isset($loginLogoutTimes[$timeIndex+1][$timeType]) &&
            $newValueTimeStamps > strtotime($loginLogoutTimes[$timeIndex+1][$timeType])
        ){
            return [
                "error" => "you can not add this time because it's greater than the next session value"
            ];
        }

        if(
            $timeType == "logout" &&
            isset($loginLogoutTimes[$timeIndex+1]["login"]) &&
            $newValueTimeStamps > strtotime($loginLogoutTimes[$timeIndex+1]["login"])
        ){
            return [
                "error" => "you can not checkout after the next session checkin"
            ];
        }


        if($timeType == 'login'){
            // check if login time will update less than logout time
            $logoutTime = $loginLogoutTimes[$timeIndex]['logout'] ?? "";

            if(!empty($logoutTime) && (strtotime($logoutTime) < $newValueTimeStamps)){
                return [
                    "error" => "check in time should be before check out time"
                ];
            }
        }
        else{
            // check if login time will update less than logout time
            $loginTime = $loginLogoutTimes[$timeIndex]['login'] ?? "";


            if(!empty($loginTime) && (strtotime($loginTime) > $newValueTimeStamps)){
                return [
                    "error" => "check out time should be after check in time"
                ];
            }

            if ($this->checkIfEmployeeIsEarlyLeaveWhenLogout($empObj, $newValue) === true){
                $data['early_leave_hours'] = $this->calculateEarlyLeaveTimeHours($empObj, $newValue);
            }
        }

        $result                 = $this->calculateHoursWhenEmployeeClickLogout($loginLogoutObj, $loginLogoutTimes);
        $data['working_hours']  = $result['total_working_hours'];
        $data['remain_hours']   = $result['remain_hours'];
        $data['overtime_hours'] = $result['overtime_hours'];

        $data['login_logout_times'] = json_encode($loginLogoutTimes);

        if ($timeType == 'login'){
            $data['late_time_hours'] = $this->calculateLateTimeHours($empObj, $request->get('value'), $loginLogoutObj);
        }

        return $data;

    }

    public function editLoginLogoutEmployeeWork(Request $request)
    {
        $loginLogoutObj     = employee_login_logout_m::getLoginLogoutById($request->get('item_id'));

        $data               = $this->returnWillSaveDataForEditLoginLogout($request, $loginLogoutObj);
        if (isset($data["error"])){
            return json_encode($data);
        }

        // update login logout times
        employee_login_logout_m::updateLoginLogoutData($loginLogoutObj->id, $data);
    }



    private function createNotCreatedDaysOnCurrentMonth($employeeObj)
    {
        $dateFrom    = date('Y-m-01');
        $dateTo      = date('Y-m-d');
        $createdDaysOnCurrentMonth     = employee_login_logout_m::getLoginLogoutTblOfCurrentMonthByEmployeeId($employeeObj->employee_id);
        $empRequiredWorkingHoursPerDay = '0'. $employeeObj->employee_required_working_hours_per_day .":00:00";


        $daysNotCreated = [];
        for($i = $dateFrom;   $i <= $dateTo; $i++)
        {
            $item = collect($createdDaysOnCurrentMonth)->where('work_date', '=', $i)->first();
            if (is_null($item))
            {
                $daysNotCreated[] = $i;
            }
        }

        if (count($daysNotCreated) > 0){

            $generalHolidays            = hr_national_holidays_m::getNationalHolidaysOfBranch($employeeObj->branch_id);
            $acceptedEmpDemandedHoliday = hr_holiday_requests_m::getAcceptedRequestsOfEmployeeInYear($employeeObj->employee_id);
            $acceptedEarlyLeave         = hr_delay_early_requests_m::getAcceptedDelayEarlyRequestsOfEmployeeInYear(
                                          $employeeObj->employee_id, 'early_leave');
            $acceptedDelayRequests      = hr_delay_early_requests_m::getAcceptedDelayEarlyRequestsOfEmployeeInYear(
                                          $employeeObj->employee_id, 'delay_request');

            $data = [];
            foreach ($daysNotCreated as $key => $day)
            {
                $data[$key]['employee_id']                  = $employeeObj->employee_id;
                $data[$key]['work_date']                    = $day;
                $data[$key]['login_logout_times']           = '';
                $data[$key]['working_hours']                = 0;
                $data[$key]['remain_hours']                 = 0;
                $data[$key]['overtime_hours']               = 0;
                $data[$key]['is_work_day']                  = $this->checkIfDayIsWokDay($employeeObj->employee_working_days, $day);
                $data[$key]['work_day_is_general_holiday']  = $this->checkIfWorkDayIsGeneralHoliday($generalHolidays, $day);
                $data[$key]['work_day_is_demanded_holiday'] = $this->checkIfWorkDayIsDemandedHoliday($acceptedEmpDemandedHoliday, $day);
                $data[$key]['work_day_has_early_leave']     = $this->checkIfWorkDayIsEarlyLeave($acceptedEarlyLeave,$day);
                $data[$key]['work_day_has_delay_request']   = $this->checkIfWorkDayIsDelayRequest($acceptedDelayRequests, $day);
                $data[$key]['created_at']                   = now();

                if (
                    $data[$key]['work_day_is_general_holiday'] == 1 ||
                    $data[$key]['work_day_is_demanded_holiday'] == 1 ||
                    $data[$key]['is_work_day'] == 0
                ) {
                    $data[$key]['should_work_hours'] = "00:00:00";
                }
                else {
                    $data[$key]['should_work_hours'] = $empRequiredWorkingHoursPerDay;
                }

            }

            employee_login_logout_m::insert($data);
        }

    }

    private function calculateHoursWhenEmployeeClickLogout($currentDayLoginLogoutObj, $loginLogoutTimes):array
    {
        $data['total_working_hours'] = $this->calculateWokHoursBasedOnLoginLogoutTimes(
            $currentDayLoginLogoutObj->should_work_hours,
            $loginLogoutTimes
        );

        $result = $this->calculateRemainHoursAndOvertimeHours(
            $currentDayLoginLogoutObj->should_work_hours ,
            $data['total_working_hours']
        );


        $data['remain_hours']   = $result['remain_hours'];
        $data['overtime_hours'] = $result['overtime_hours'];
        $data['working_hours']  = $result['working_hours'];

        return $data;
    }

    private function calculateWokHoursBasedOnLoginLogoutTimes($shouldWorkHours, $loginLogoutTimes):string
    {
        $totalWorkMinutes = [];

        foreach ($loginLogoutTimes as $key => $time){
            $totalWorkMinutes[$key]  = intval((strtotime($time['logout']) - strtotime($time['login'])) / 60);
        }

        $totalWorkMinutes = array_sum($totalWorkMinutes);
        return intdiv($totalWorkMinutes, 60) . ':' . ($totalWorkMinutes % 60) . ':00';
    }

    private function calculateRemainHoursAndOvertimeHours($shouldWorkHours , $totalWorkingHours):array
    {

        $shouldWorkHours                           = explode(':', $shouldWorkHours);
        $totalWorkingHours                         = explode(':', $totalWorkingHours);
        $diffBetweenShouldWorkHoursAndWorkHours[0] = $shouldWorkHours[0] - $totalWorkingHours[0];

        if ($totalWorkingHours[1] > $shouldWorkHours[1] & $shouldWorkHours[0] - 1 > $totalWorkingHours[0]){
            $diffBetweenShouldWorkHoursAndWorkHours[0] -= 1;
            $shouldWorkHours[1]                        += 60;
        }


        $diffBetweenShouldWorkHoursAndWorkHours[1] = $shouldWorkHours[1] - $totalWorkingHours[1];
        $diffBetweenShouldWorkHoursAndWorkHours[2] = '00';

        if ($diffBetweenShouldWorkHoursAndWorkHours[0] > 0)
        {
            $data['remain_hours']   = implode(':', $diffBetweenShouldWorkHoursAndWorkHours);
            $data['overtime_hours'] = '00:00:00';
            $data['working_hours']  = $totalWorkingHours;
        }
        else {
            $data['remain_hours']                      = '00:00:00';
            $diffBetweenShouldWorkHoursAndWorkHours[0] = abs($diffBetweenShouldWorkHoursAndWorkHours[0]);
            $diffBetweenShouldWorkHoursAndWorkHours[1] = abs($diffBetweenShouldWorkHoursAndWorkHours[1]);
            $data['overtime_hours']                    = implode(':', $diffBetweenShouldWorkHoursAndWorkHours);
            $data['working_hours']                     = implode(':', $shouldWorkHours);
        }


        return $data;
    }

    private function checkIfDayIsWokDay($employeeWorkingDays, $day): string
    {
        $dayName             = date('l', strtotime($day));
        $employeeWorkingDays = json_decode($employeeWorkingDays, true);

        if (in_array(strtolower($dayName), $employeeWorkingDays))
        {
            return '1';
        }

        return '0';

    }

    private function checkIfWorkDayIsGeneralHoliday($generalHolidays, $day): string
    {
        if (empty($generalHolidays))
        {
            return '0';
        }

        $generalHolidaysDates = $generalHolidays->pluck('holiday_date')->toArray();
        if (in_array($day, $generalHolidaysDates)){
            return '1';
        }

        return '0';

    }

    private function checkIfWorkDayIsDemandedHoliday($acceptedEmpDemandedHoliday, $day): string
    {

        if (empty($acceptedEmpDemandedHoliday)){
            return '0';
        }

        $acceptedEmpDemandedHolidayDates = collect($acceptedEmpDemandedHoliday)->pluck('req_date')->toArray();
        if(in_array($day, $acceptedEmpDemandedHolidayDates)){
            return '1';
        }

        return '0';
    }

    private function checkIfWorkDayIsEarlyLeave($acceptedEarlyLeave, $day):string
    {
        if (empty($acceptedEarlyLeave)){
            return '0';
        }

        $acceptedEarlyLeaveDates = collect($acceptedEarlyLeave)->pluck('req_date')->toArray();
        if(in_array($day, $acceptedEarlyLeaveDates)){
            return '1';
        }

        return '0';

    }

    private function checkIfWorkDayIsDelayRequest($acceptedDelayRequests, $day):string
    {
        if (empty($acceptedDelayRequests)){
            return '0';
        }

        $acceptedDelayRequestsDates = collect($acceptedDelayRequests)->pluck('req_date')->toArray();
        if(in_array($day, $acceptedDelayRequestsDates)){
            return '1';
        }

        return '0';
    }

    private function checkIfEmployeeIsLateWhenLogin($employeeObj, $loginTime = null):bool
    {
        $empShouldStartWorkAt = date('H:i',strtotime($employeeObj->employee_should_start_work_at));
        $currentDay           = now()->format('Y-m-d');

        if(is_null($loginTime)){
            $currentTime = now()->format('H:i');
        }
        else{
            $currentTime = date('H:i', strtotime($loginTime));
        }


        $acceptedDelayRequestsOnCurrentDay = hr_delay_early_requests_m::getAcceptedDelayEarlyRequestsOfEmployeeInYear(
                                             $employeeObj->employee_id, 'delay_request')->
                                             where('req_date','=', $currentDay)->toArray();

        if(!empty($acceptedDelayRequestsOnCurrentDay)){
            return false;
        }

        if(strtotime($currentTime) > strtotime($empShouldStartWorkAt)){
            return true;
        }

        return false;
    }

    private function calculateLateTimeHours($employeeObj, $loginTime, $loginLogoutObj):string
    {
        $empShouldStartWorkAt = new Carbon($employeeObj->employee_should_start_work_at);

        if (
            $loginLogoutObj->work_day_is_general_holiday ||
            $loginLogoutObj->work_day_is_demanded_holiday
        ){
            return "";
        }

        if (is_null($loginTime)){
            $currentTime = new Carbon(now()->format('H:i:s'));
        }
        else{
            $currentTime = date('H:i:s', strtotime($loginTime));
        }

        return $empShouldStartWorkAt->diff($currentTime)->format('%H:%I:%S');
    }

    private function checkIfEmployeeIsEarlyLeaveWhenLogout($employeeObj, $logoutTime = null):bool
    {
        $empShouldEndWorkAt = date('H:i', strtotime($employeeObj->employee_should_end_work_at));
        $currentDay         = now()->format('Y-m-d');

        if(is_null($logoutTime)){
            $currentTime = now()->format('H:i');
        }
        else{
            $currentTime = date('H:i', strtotime($logoutTime));
        }


        $acceptedEarlyLeaveOnCurrentDay = hr_delay_early_requests_m::getAcceptedDelayEarlyRequestsOfEmployeeInYear(
            $employeeObj->employee_id, 'early_leave')->
            where('req_date','=', $currentDay)->toArray();


        if(!empty($acceptedEarlyLeaveOnCurrentDay)){
            return false;
        }

        if(strtotime($currentTime) < strtotime($empShouldEndWorkAt)){
            return true;
        }

        return false;
    }

    private function calculateEarlyLeaveTimeHours($employeeObj, $logoutTime=null):string
    {
        $empShouldEndWorkAt = new Carbon($employeeObj->employee_should_end_work_at);

        if(is_null($logoutTime)){
            $currentTime = new Carbon(now()->format('H:i:s'));
        }
        else{
            $currentTime = date('H:i:s', strtotime($logoutTime));
        }


        return $empShouldEndWorkAt->diff($currentTime)->format('%H:%I:%S');
    }



}
