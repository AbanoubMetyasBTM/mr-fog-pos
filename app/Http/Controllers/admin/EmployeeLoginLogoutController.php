<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_paycheck_m;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeLoginLogoutController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employee Login Logout");

    }


    public function index(Request $request, $employeeId)
    {

        havePermissionOrRedirect("admin/employee_login_logout", "show_action");
        $this->data["header"]           = 'Employee Check In Check Out Table';
        $this->data["add_login_logout"] = false;
        $employeeObj                    = employee_details_m::getEmployeeDataByEmployeeId($employeeId);

        if (!is_object($employeeObj)){
            abort(404);
        }


        $conds = $request->all();

        if (isset($conds['month']) && !empty($conds['month']) && isset($conds['year']) && !empty($conds['year'])){

            $conds['date']           = $conds['year'] . "-" . $conds['month'];
            $this->data["date_from"] = date('Y-m-01', strtotime($conds['date']));
            $this->data["date_to"]   = date('Y-m-t', strtotime($conds['date']));
            $this->data["year"]      = date('Y', strtotime($conds['date']));
            $this->data["month"]     = date('m', strtotime($conds['date']));
        }
        else{
            $this->data["date_from"] = date('Y-m-01');
            $this->data["date_to"]   = date('Y-m-t');
            $this->data["year"]      = date('Y');
            $this->data["month"]     = date('m');
        }


        $this->data["current_day_login_logout"] = employee_login_logout_m::getCurrentDayLoginLogoutTblByEmployeeId($employeeId);
        $this->data["results"]                  = employee_login_logout_m::getLoginLogoutTblOfCurrentMonthByEmployeeId($employeeId, $conds);
        $this->data["employee_data"]            = $employeeObj;
        $this->data["branch_data"]              = branches_m::getBranchById($employeeObj->branch_id);
        $this->data["total_should_work_hours"]  = calculateTotalTime($this->data["results"]->pluck('should_work_hours')->all());
        $this->data["total_working_hours"]      = calculateTotalWorkHoursAfterOvertimeHours(
            $this->data["results"]->pluck('working_hours')->all(),
            $this->data["results"]->pluck('overtime_hours')->all(),
            $employeeObj->employee_overtime_hour_rate
        );

        $this->data['paychecks'] = hr_paycheck_m::getPaychecksOfEmployeeByYearAndMonth($employeeId,  $this->data["year"] , $this->data["month"]);


        return $this->returnView($request, "admin.subviews.employee_hr.subviews.employee_login_logout.show");


    }




}
