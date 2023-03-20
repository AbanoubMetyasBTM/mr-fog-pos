<?php

namespace App\Http\Controllers\admin;

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
        havePermissionOrRedirect("admin/employees_tasks", "show_action");

        $this->data["request_data"] = (object)$request->all();
        $conds                      = $request->all();
        $conds['paginate']          = 50;
        $this->data["results"]      = $this->modelClass::getEmployeesTasks($conds);
        $this->data["branches"]     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["allEmployees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }


    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/employees_tasks", $item_id == null ? "add_action" : "edit_action");
    }


    public function getEditObj(Request $request, $item_id)
    {
        $itemObj = employee_tasks_m::findOrFail($item_id);
        if ($this->branch_id!=null){
            $this->checkIfEmployeeInSpecificBranchHasTask($item_id);
        }

        return $itemObj;
    }

    public function beforeAddNewRow(Request $request)
    {
        $allEmployees = employee_details_m::getEmployeesOrOrSpecificBranch()->pluck('employee_id');
        if (!in_array($request['employee_id'], $allEmployees->toArray())){
            abort(404);
            die();
        }

        $request->request->add(['task_status' => 'pending']);
        return $request;
    }

    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/employees_tasks", "delete_action");
        $this->checkIfEmployeeInSpecificBranchHasTask($request['item_id']);
    }

    public function showEmployeeTask(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/employees_tasks", "show_task");

        $this->checkIfEmployeeInSpecificBranchHasTask($item_id);

        $this->data["task"]     = employee_tasks_m::getEmployeeTaskById($item_id);
        $this->data["comments"] = employee_task_comments_m::getCommentsByTaskId($item_id);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show_task");

    }

    public function changeStatusOfTask(Request $request)
    {

        havePermissionOrRedirect("admin/employees_tasks", "accept_task");


        if (!in_array($request['accept'], ["under_review", "done", "in_progress", "reviewed"])) {
            echo json_encode([
                "error" => "you can not edit this"
            ]);
            return;
        }

        if ($request['accept'] == "under_review") {
            return $this->new_accept_item($request);
        }

        $old_obj = $this->modelClass::where('task_id', '=', $request->get("item_id"))->first();

        createLog($request, [
            'user_id'        => $this->user_id,
            'module'         => "Employee-Tasks",
            'module_content' => json_encode($request->all()),
            'action_url'     => url()->full(),
            'action_type'    => 'change-Status-Of-Task',
            'old_obj'        => $old_obj,
        ]);

        $old_obj->update([
            'task_status' => $request['accept']
        ]);

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
}
