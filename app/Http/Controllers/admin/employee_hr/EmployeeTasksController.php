<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\form_builder\EmployeesTasksBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_task_comments_m;
use App\models\employee\employee_tasks_m;
use App\models\employee\employee_warnings_m;
use App\User;
use Illuminate\Http\Request;

class EmployeeTasksController extends AdminBaseController
{

    use CrudTrait;

    /** @var employee_tasks_m */
    public $modelClass;


    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employees Tasks");

        $this->modelClass   = employee_tasks_m::class;
        $this->viewSegment  = "employees_tasks";
        $this->routeSegment = "employees-tasks";
        $this->builderObj   = new EmployeesTasksBuilder();
        $this->primaryKey   = "task_id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/my_hr_employees_tasks", "show_action");

        if (!empty($request->all())){
            $this->checkIfGetResultsKeyWordsAvailableForEmployee($request);
        }

        $this->data["request_data"] = (object)$request->all();
        $conds                      = $request->all();
        $conds['paginate']          = 50;
        $conds['employee_id']       = $this->user_id;
        $this->data["results"]      = $this->modelClass::getEmployeesTasks($conds);

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.$this->viewSegment.show");
    }

    private function checkIfEmployeeHasTask($employeeId, $taskId)
    {
        $taskObj = $this->modelClass::getEmployeeTaskByIdAndEmployeeId($taskId, $employeeId);
        if (!is_object($taskObj)){
            abort(404);
            die();
        }
    }

    public function showEmployeeTask(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/my_hr_employees_tasks", "show_task");

        $this->checkIfEmployeeInSpecificBranchHasTask($item_id);
        $this->checkIfEmployeeHasTask($this->user_id, $item_id);
        $this->data["task"]     = employee_tasks_m::getEmployeeTaskById($item_id);
        $this->data["comments"] = employee_task_comments_m::getCommentsByTaskId($item_id);

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.$this->viewSegment.show_task");
    }

    public function changeStatusOfTask(Request $request)
    {
        havePermissionOrRedirect("admin/my_hr_employees_tasks", "work_on_task");

        if (!in_array($request['accept'], ["done", "in_progress"])) {
            echo json_encode([
                "error" => "you can not edit this"
            ]);
            return;
        }

        // update status of task
        $this->modelClass::where('task_id', '=', $request->get("item_id"))->update([
            'task_status' => $request['accept']
        ]);

        if ($request['accept'] == "in_progress") {
            return $this->new_accept_item($request);
        }

        return json_encode([
            "msg" => capitalize_string($request['accept'])
        ]);
    }

    private function checkIfEmployeeInSpecificBranchHasTask($taskId)
    {
        $taskObj = $this->modelClass::getEmployeeTaskById($taskId);
        $allEmployees = employee_details_m::getEmployeesOrOrSpecificBranch()->pluck('employee_id');

        if (!in_array($taskObj->employee_id, $allEmployees->toArray())){
            abort(404);
            die();
        }
    }

    private function checkIfGetResultsKeyWordsAvailableForEmployee(Request $request)
    {

        $availableCondsForEmployee = [
            'req_status',
            'date_from',
            'date_to',
            'load_inner'
        ];

        foreach ($request->all() as  $keyword => $value){

            if (!in_array($keyword, $availableCondsForEmployee)){
                abort(404);
                die();
            }
        }

    }


}
