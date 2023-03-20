<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_login_logout_m;
use App\models\hr\hr_delay_early_requests_m;
use App\User;
use Illuminate\Http\Request;

class DelayEarlyRequestsController extends AdminBaseController
{

    use CrudTrait;

    /** @var hr_delay_early_requests_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->modelClass   = hr_delay_early_requests_m::class;
        $this->viewSegment  = "delay_early_requests";
        $this->routeSegment = "delay-early-requests";
        $this->primaryKey   = "id";

    }

    public function index(Request $request, $type)
    {

        havePermissionOrRedirect("admin/delay_early_requests", "show_action");

        $requestTypes = [
            'delay' => 'delay_request',
            'early' => 'early_leave'
        ];

        $headersText = [
            'delay_header' => 'Delay Requests',
            'early_header' => 'Early Leave Requests',
        ];


        if (!in_array($type, array_keys($requestTypes))) {

            return redirect()->back();
        }

        $conds                      = $request->all();
        $conds['req_type']          = $requestTypes[$type];
        $delayEarlyRequests         = $this->modelClass::getAllDelayEarlyRequests($conds);
        $this->data["request_data"] = (object)$request->all();
        $this->data["header"]       = $headersText[$type . "_header"];
        $this->data["results"]      = $delayEarlyRequests;
        $this->data["branches"]     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["allEmployees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        $this->setMetaTitle($headersText[$type . "_header"]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }


    public function changeStatusOfRequest(Request $request)
    {

        havePermissionOrRedirect("admin/delay_early_requests", "accept_action");


        $delayEarlyRequestObj = hr_delay_early_requests_m::findOrFail($request->get('item_id'));
        $empId                = $delayEarlyRequestObj->employee_id;
        $requestType          = $delayEarlyRequestObj->req_type;

        $allEmployees = employee_details_m::getEmployeesOrOrSpecificBranch()->pluck('employee_id');

        if (!in_array($empId, $allEmployees->toArray())){
            abort(404);
            die();
        }


        if ($request['accept'] == "accepted") {
            $check = $this->checkIfDelayEarlyRequestsLessThanMax($empId, $requestType);
            if ($check == false) {
                return json_encode([
                    "error" => "This request cannot be approved because this employee has exceeded the maximum limit"
                ]);
            }
        }

        if ($request['accept'] == "pending") {
            return $this->new_accept_item($request);
        }

        createLog($request, [
            'item_id'        => $request['item_id'],
            'user_id'        => $this->user_id,
            'module'         => 'Delay-Early-Requests',
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'change-Status-Of-Request',
            'old_obj'        => $delayEarlyRequestObj,
        ]);

        // update status of request
        hr_delay_early_requests_m::updateStatusOfDelayEarlyRequest($request['item_id'], $request['accept']);

        $this->updateWorkDayToAcceptedDelayEarlyRequestForEmployee($request['item_id'], $empId);

        return json_encode([
            "msg"     => capitalize_string($request['accept']),
            "item_id" => $request->get('item_id')
        ]);

    }


    private function checkIfDelayEarlyRequestsLessThanMax($employeeId, $requestType): bool
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


    private function updateWorkDayToAcceptedDelayEarlyRequestForEmployee($delayEarlyRequestId, $employeeId)
    {
        $delayEarlyRequestObj = hr_delay_early_requests_m::getDelayEarlyRequestById($delayEarlyRequestId);

        if ($delayEarlyRequestObj->req_status == 'accepted')
        {
            if ($delayEarlyRequestObj->req_type == 'delay_request'){
                $data = [
                    'work_day_has_delay_request' => '1'
                ];
            }
            else
            {
                $data = [
                    'work_day_has_early_leave' => '1'
                ];
            }

            employee_login_logout_m::updateLoginLogoutDataByEmployeesIds
            (
                [$employeeId],
                $delayEarlyRequestObj->req_date,
                $data
            );
        }

    }
}
