<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\form_builder\HolidayRequestsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\Http\Controllers\traits\MyEmployeeHrTrait;
use App\models\employee\employee_details_m;
use App\models\hr\hr_delay_early_requests_m;
use App\models\hr\hr_holiday_requests_m;
use App\models\hr\hr_national_holidays_m;
use Illuminate\Http\Request;

class HolidayRequestsController extends AdminBaseController
{

    use CrudTrait;
    use MyEmployeeHrTrait;

    /** @var hr_holiday_requests_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->modelClass   = hr_holiday_requests_m::class;
        $this->viewSegment  = "employee_hr.subviews.holiday_requests";
        $this->routeSegment = "holiday-requests";
        $this->builderObj   = new HolidayRequestsBuilder();
        $this->primaryKey   = "id";

    }

    public function index(Request $request, $type)
    {
        havePermissionOrRedirect("admin/my_hr_national_holidays", "show_action");
        $holidayTypes = [
            'sick'     => 'sick_holiday',
            'vacation' => 'vacation'
        ];

        $headersText = [
            'sick_header'     => 'My Sick Holiday Requests',
            'vacation_header' => 'My Vacation Requests',
        ];



        if (!in_array($type, array_keys($holidayTypes))) {
            return redirect()->back();
        }


        if (!empty($request->all())){
            $availableCondsForEmployee = [
                'req_status',
                'date_from',
                'date_to',
                'load_inner'
            ];

            $this->checkIfGetResultsKeyWordsAvailableForEmployee($request, $availableCondsForEmployee);
        }


        $conds                      = $request->all();
        $conds['req_type']          = $holidayTypes[$type];
        $conds['employee_id']       = $this->user_id;
        $data                       = $this->modelClass::getAllHolidayRequests($conds);
        $this->data["request_data"] = (object)$request->all();
        $this->data["header"]       = $headersText[$type . "_header"];
        $this->data["type"]         = $holidayTypes[$type];
        $this->data["results"]      = $data;


        $this->setMetaTitle($headersText[$type . "_header"]);

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.holiday_requests.show");

    }

    public function beforeDoAnythingAtSave(Request $request)
    {
        havePermissionOrRedirect("admin/my_hr_national_holidays","add_action");
        $requestsTypes = ['sick_holiday','vacation'];

        $requestType = $request->get("type");

        if (!in_array($requestType, $requestsTypes)) {

           abort(404);
        }

    }

    public function customValidation(Request $request, $item_id = null)
    {
        $requestType = $request->get('type');


        $requestTypes = [
            'sick_holiday',
            'vacation'
        ];

        if (!in_array($requestType, $requestTypes)) {
            return abort(404);
            die();
        }

        $types = [
            'sick_holiday' => 'sick',
            'vacation'     => 'vacation'
        ];


        if ($this->checkIfDayIsWorkDay($request->get('req_date')) === false){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/holiday-requests/show/".$types[$requestType],
                "It is not possible to request vacation on this date because it is a day off"
            );

        }

        if ($this->checkIfDayIsGeneralHoliday($request->get('req_date')) === true){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/holiday-requests/show/".$types[$requestType],
                "It is not possible to request vacation on this date because it is a general holiday"
            );
        }

        if ($this->checkIfHolidayRequestsLessThanMax($this->user_id, $requestType) === false) {
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/holiday-requests/show/".$types[$requestType],
                "This request cannot be saved because you have exceeded the maximum limit"
            );

        }

        return true;
    }


    public function beforeAddNewRow(Request $request)
    {

        $requestType = $request->get('type');

        $request->request->add([
            'employee_id'    => $this->user_id,
            'req_status' => 'pending',
            'req_type'   => $requestType
        ]);

        return $request;
    }


    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {

        $requestTypes = [
            'sick_holiday' => 'sick',
            'vacation'     => 'vacation'
        ];

        $requestType = $requestTypes[$item_obj->req_type];

        return [
            "msg"      => "Saved Successfully",
            "redirect" => url("admin/employee-hr/holiday-requests/show/$requestType")
        ];
    }

    private function checkIfHolidayRequestsLessThanMax($employeeId, $holidayRequestsType): bool
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



}
