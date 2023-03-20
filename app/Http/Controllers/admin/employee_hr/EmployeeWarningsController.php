<?php

namespace App\Http\Controllers\admin\employee_hr;

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

        havePermissionOrRedirect("admin/my_hr_employees_warnings", "show_action");

        if (!empty($request->all())){
            $this->checkIfGetResultsKeyWordsAvailableForEmployee($request);
        }

        $this->data["request_data"] = (object)$request->all();
        $conds                      = $request->all();
        $conds['employee_id']       = $this->user_id;
        $this->data["results"]      = $this->modelClass::getAllEmployeesWarnings($conds);

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.$this->viewSegment.show");

    }


    private function checkIfGetResultsKeyWordsAvailableForEmployee(Request $request)
    {

        $availableCondsForEmployee = [
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
