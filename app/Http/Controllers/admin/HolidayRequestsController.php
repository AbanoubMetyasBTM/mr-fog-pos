<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_delay_early_requests_m;
use App\models\hr\hr_holiday_requests_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayRequestsController extends AdminBaseController
{

    use CrudTrait;

    /** @var hr_holiday_requests_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->modelClass   = hr_holiday_requests_m::class;
        $this->viewSegment  = "holiday_requests";
        $this->routeSegment = "holiday-requests";
        $this->primaryKey   = "id";

    }

    public function index(Request $request, $type)
    {
        havePermissionOrRedirect("admin/holiday_requests", "show_action");
        $holidayTypes = [
            'sick'     => 'sick_holiday',
            'vacation' => 'vacation'
        ];

        $headersText = [
            'sick_header'     => 'Sick Holiday Requests',
            'vacation_header' => 'Vacation Requests',
        ];


        if (!in_array($type, array_keys($holidayTypes))) {

            return redirect()->back();
        }

        $conds                      = $request->all();
        $conds['req_type']          = $holidayTypes[$type];
        $data                       = $this->modelClass::getAllHolidayRequests($conds);
        $this->data["request_data"] = (object)$request->all();
        $this->data["header"]       = $headersText[$type . "_header"];
        $this->data["results"]      = $data;
        $this->data["branches"]     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["allEmployees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        $this->setMetaTitle($headersText[$type . "_header"]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }


    public function changeStatusOfRequest(Request $request)
    {

        havePermissionOrRedirect("admin/holiday_requests", "accept_action");

        $holidayRequestObj = hr_holiday_requests_m::findOrFail($request->get('item_id'));
        $empId             = $holidayRequestObj->employee_id;
        $requestType       = $holidayRequestObj->req_type;

        $allEmployees = employee_details_m::getEmployeesOrOrSpecificBranch()->pluck('employee_id');

        if (!in_array($empId, $allEmployees->toArray())){
            abort(404);
            die();
        }

        if ($request['accept'] == "accepted") {
            $check = $this->checkIfHolidayRequestsLessThanMax($empId, $requestType);
            if ($check === false) {
                return json_encode([
                    "error" => "This request cannot be approved because this employee has exceeded the maximum limit"
                ]);
            }
        }

        if ($request['accept'] == "pending") {
            return $this->new_accept_item($request);
        }

        // update status of request
        hr_holiday_requests_m::updateStatusOfHolidayRequest($request->get('item_id'), $request['accept']);

        $this->updateWorkDayToAcceptedHolidayRequestForEmployee($request->get('item_id'), $empId);

        createLog($request, [
            'user_id'        => $this->user_id,
            'module'         => 'Holiday-Requests',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'change-Status-Of-Request',
        ]);

        return json_encode([
            "msg"     => capitalize_string($request['accept']),
            "item_id" => $request->get('item_id')
        ]);

    }


    private function checkIfHolidayRequestsLessThanMax($employeeId, $holidayRequestsType):bool
    {
        $holidayRequestsTypes = [
            'sick_holiday' => 'employee_sick_vacation_max_requests',
            'vacation'     => 'employee_vacation_max_requests',
        ];

        $employeeObj           = employee_details_m::getEmployeeDataByEmployeeId($employeeId);
        $countOfMaxRequests    = $employeeObj->{$holidayRequestsTypes[$holidayRequestsType]};
        $countAcceptedRequests = count(hr_holiday_requests_m::getAcceptedRequestsOfEmployeeInYear($employeeId, $holidayRequestsType)->
        toArray());

        if ($countAcceptedRequests < $countOfMaxRequests) {
            return true;
        }

        return false;
    }


    private function updateWorkDayToAcceptedHolidayRequestForEmployee($holidayRequestId, $employeeId)
    {
        $holidayRequestObj = hr_holiday_requests_m::getHolidayRequestById($holidayRequestId);

        if ($holidayRequestObj->req_status == 'accepted')
        {
            employee_login_logout_m::updateLoginLogoutDataByEmployeesIds
            (
                [$employeeId],
                $holidayRequestObj->req_date,
                [
                    'work_day_is_demanded_holiday' => '1',
                    'should_work_hours'            => '00:00:00'
                ]
            );
        }

    }


}
