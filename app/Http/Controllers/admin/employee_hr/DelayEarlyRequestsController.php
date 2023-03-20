<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\form_builder\DelayEarlyRequestsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\Http\Controllers\traits\MyEmployeeHrTrait;
use App\models\employee\employee_details_m;
use App\models\hr\hr_delay_early_requests_m;
use App\models\hr\hr_national_holidays_m;
use Illuminate\Http\Request;

class DelayEarlyRequestsController extends AdminBaseController
{

    use CrudTrait;
    use MyEmployeeHrTrait;

    /** @var hr_delay_early_requests_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->modelClass   = hr_delay_early_requests_m::class;
        $this->viewSegment  = "employee_hr.subviews.delay_early_requests";
        $this->routeSegment = "delay-early-requests";
        $this->builderObj   = new DelayEarlyRequestsBuilder();
        $this->primaryKey   = "id";

    }

    public function index(Request $request, $type)
    {

        havePermissionOrRedirect("admin/my_hr_delay_early_requests", "show_action");

        $requestTypes = [
            'delay' => 'delay_request',
            'early' => 'early_leave'
        ];

        $headersText = [
            'delay_header' => 'My Delay Requests',
            'early_header' => 'My Early Leave Requests',
        ];

        if (!in_array($type, array_keys($requestTypes))) {
            return abort(404);
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
        $conds['req_type']          = $requestTypes[$type];
        $conds['employee_id']       = $this->user_id;
        $delayEarlyRequests         = $this->modelClass::getAllDelayEarlyRequests($conds);
        $this->data["request_data"] = (object)$request->all();
        $this->data["header"]       = $headersText[$type . "_header"];
        $this->data["type"]         = $requestTypes[$type];

        $this->data["results"]      = $delayEarlyRequests;

        $this->setMetaTitle($headersText[$type . "_header"]);
        return $this->returnView($request, "admin.subviews.employee_hr.subviews.delay_early_requests.show");

    }

    public function beforeDoAnythingAtSave(Request $request)
    {
        havePermissionOrRedirect("admin/my_hr_delay_early_requests","add_action");
        $requestsTypes = ['delay_request','early_leave'];

        $requestType = $request->get("type");

        if (!in_array($requestType, $requestsTypes)) {
            return $this->returnMsgWithRedirection(
                $request,
                "admin/dashboard",
                "you can not edit"
            );
        }
    }

    public function beforeSaveRow(Request $request)
    {
        $request["req_wanted_time"] = $request["req_wanted_time"].":0:0";

        return $request;
    }

    public function customValidation(Request $request, $item_id = null)
    {

        $requestType = $request->get('type');

        $requestTypes = [
            'delay_request',
            'early_leave'
        ];


        if (!in_array($requestType, $requestTypes)) {
            return abort(404);
            die();
        }

        $types = [
            'early_leave'   => 'early',
            'delay_request' => 'delay'
        ];

        if ($this->checkIfDayIsWorkDay($request->get('req_date')) === false){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/delay-early-requests/show/".$types[$requestType],
                "It is not possible to request vacation on this date because it is a day off"
            );
        }

        if ($this->checkIfDayIsGeneralHoliday($request->get('req_date')) === true){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/delay-early-requests/show/".$types[$requestType],
                "It is not possible to request vacation on this date because it is a general holiday"
            );
        }


        if ($this->checkIfDayIsDemandedHoliday($request->get('req_date')) === true){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/delay-early-requests/show/".$types[$requestType],
                "It is not possible to request vacation on this date because it is a demanded holiday"
            );
        }

        if ($this->checkIfDelayEarlyRequestsLessThanMax($this->user_id, $requestType) === false) {
            return $this->returnMsgWithRedirection(
                $request,
                "admin/employee-hr/delay-early-requests/show/".$types[$requestType],
                "This request cannot be saved because you have exceeded the maximum limit"
            );

        }

        return true;
    }

    public function beforeAddNewRow(Request $request)
    {
        $request->request->add([
            'employee_id' => $this->user_id,
            'req_status'  => 'pending',
            'req_type'    => $request->get('type')
        ]);

        return $request;
    }

    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {
        $requestTypes = [
            'delay_request' => 'delay',
            'early_leave'   => 'early'
        ];

        $requestType = $requestTypes[$item_obj->req_type];

        return [
            "msg"      => "Saved Successfully",
            "redirect" => url("admin/employee-hr/delay-early-requests/show/$requestType")
        ];
    }

    private function checkIfDelayEarlyRequestsLessThanMax($employeeId, $requestType)
    {
        $DelayEarlyRequestsTypes = [
            'delay_request' => 'employee_delay_requests_max_requests',
            'early_leave'   => 'employee_early_requests_max_requests',
        ];

        $employeeObj           = employee_details_m::getEmployeeDataByEmployeeId($employeeId);
        $countOfMaxRequests    = $employeeObj->{$DelayEarlyRequestsTypes[$requestType]};
        $countAcceptedRequests = count(hr_delay_early_requests_m::getAcceptedDelayEarlyRequestsOfEmployeeInYear(
                                 $employeeId, $requestType)->toArray());

        if ($countAcceptedRequests < $countOfMaxRequests) {
            return true;
        }
        return false;
    }


}
