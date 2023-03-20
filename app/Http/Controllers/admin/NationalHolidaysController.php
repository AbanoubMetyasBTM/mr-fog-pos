<?php

namespace App\Http\Controllers\admin;

use App\form_builder\NationalHolidaysBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_national_holidays_m;
use Illuminate\Http\Request;

class NationalHolidaysController extends AdminBaseController
{

    use CrudTrait;

    /** @var hr_national_holidays_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("National Holidays");

        $this->modelClass          = hr_national_holidays_m::class;
        $this->viewSegment         = "national_holidays";
        $this->routeSegment        = "national-holidays";
        $this->builderObj          = new NationalHolidaysBuilder();
        $this->primaryKey          = "id";

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/national_holidays", "show_action");

        $nationalHolidays = $this->modelClass::getAllNationalHolidays();



        $this->data["results"] = $nationalHolidays;
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }


    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/national_holidays", $item_id == null ? "add_action" : "edit_action");
    }


    public function afterSave(Request $request, $item_obj)
    {
        $this->updateWorkDayToNationalHolidayForEmployees($item_obj);
    }

    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/national_holidays", "delete_action");
    }


    private function updateWorkDayToNationalHolidayForEmployees($nationalHolidayObj)
    {
        $branchesIdsHaveNationalHoliday = branches_m::getAllBranches()->
                                          where('branch_country', '=', $nationalHolidayObj->country_name)->
                                          pluck('branch_id')->toArray();

        $employeesIdsFollowBranchesHaveNationalHoliday = collect(employee_details_m::getEmployees())->
                                                         whereIn('branch_id', $branchesIdsHaveNationalHoliday)->
                                                         pluck('employee_id')->toArray();

        employee_login_logout_m::updateLoginLogoutDataByEmployeesIds
        (
            $employeesIdsFollowBranchesHaveNationalHoliday,
            $nationalHolidayObj->holiday_date,
            [
                'work_day_is_general_holiday' => '1',
                'should_work_hours'           => '00:00:00'
            ]
        );
    }
}
