<?php

namespace App\Http\Controllers\traits;

use App\models\employee\employee_details_m;
use App\models\hr\hr_holiday_requests_m;
use App\models\hr\hr_national_holidays_m;
use Illuminate\Http\Request;

trait MyEmployeeHrTrait
{
    public function checkIfDayIsGeneralHoliday($day): bool
    {
        $day            = date('Y-m-d', strtotime($day));
        $branchHolidays = hr_national_holidays_m::getNationalHolidaysOfBranch($this->branch_id)->pluck('holiday_date')->toArray();

        if (in_array($day, $branchHolidays)){
            return true;
        }
        return false;
    }

    public function checkIfDayIsWorkDay($day): bool
    {
        $dayName             = date('l', strtotime($day));
        $empObj = employee_details_m::getEmployeeDataByEmployeeId($this->user_id);

        $employeeWorkingDays = json_decode($empObj->employee_working_days, true);

        if (in_array(strtolower($dayName), $employeeWorkingDays))
        {
            return true;
        }
        return false;
    }

    public function checkIfDayIsDemandedHoliday($day): bool
    {
        $day                      = date('Y-m-d', strtotime($day));
        $acceptedDemandedHolidays = hr_holiday_requests_m::getAcceptedRequestsOfEmployeeInYear($this->user_id)->
                                    pluck('req_date')->toArray();

        if (in_array(($day), $acceptedDemandedHolidays)) {
            return true;
        }
        return false;
    }

    private function checkIfGetResultsKeyWordsAvailableForEmployee(Request $request, $availableCondsForEmployee)
    {
        foreach ($request->all() as  $keyword => $value){

            if (!in_array($keyword, $availableCondsForEmployee)){
                abort(404);
                die();
            }
        }

    }
}
