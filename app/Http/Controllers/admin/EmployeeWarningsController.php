<?php

namespace App\Http\Controllers\admin;

use App\form_builder\EmployeesWarningsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\employee\employee_details_m;
use App\models\employee\employee_warnings_m;
use App\User;
use Illuminate\Http\Request;

class EmployeeWarningsController extends AdminBaseController
{

    use CrudTrait;

    /** @var employee_warnings_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employees Warnings");

        $this->modelClass   = employee_warnings_m::class;
        $this->viewSegment  = "employees_warnings";
        $this->routeSegment = "employees-warnings";
        $this->builderObj   = new EmployeesWarningsBuilder();
        $this->primaryKey   = "id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/employees_warnings", "show_action");

        $this->data["request_data"] = (object)$request->all();
        $conds                      = $request->all();
        $this->data["results"]      = $this->modelClass::getAllEmployeesWarnings($conds);
        $this->data["branches"]     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["allEmployees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/employees_warnings", $item_id == null ? "add_action" : "edit_action");
    }

    public function getEditObj(Request $request, $item_id)
    {
        $itemObj = employee_warnings_m::findOrFail($item_id);
        if ($this->branch_id!=null){
            $this->checkIfEmployeeInSpecificBranchHasWarning($item_id);
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

        return $request;
    }



    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/employees_warnings", "delete_action");
        $this->checkIfEmployeeInSpecificBranchHasWarning($request['item_id']);
    }



    private function checkIfEmployeeInSpecificBranchHasWarning($warningId)
    {
        $warningObj = $this->modelClass::getEmployeeWarningById($warningId);
        $allEmployees = employee_details_m::getEmployeesOrOrSpecificBranch()->pluck('employee_id');

        if (!in_array($warningObj->employee_id, $allEmployees->toArray())){
            abort(404);
            die();
        }
    }

}
