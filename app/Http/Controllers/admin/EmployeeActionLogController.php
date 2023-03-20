<?php

namespace App\Http\Controllers\admin;

use App\form_builder\TaxesGroupsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\employee_action_log_m;
use App\models\taxes_groups_m;
use App\User;
use Illuminate\Http\Request;

class EmployeeActionLogController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employee Actions Log");
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/employee_action_log","show_action");

        $requestData             = $request->all();
        $requestData["paginate"] = 50;
        $this->data["results"]   = employee_action_log_m::getAllUsersActionLog($requestData);

        $this->data["branches"]     = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["allEmployees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        return $this->returnView($request,"admin.subviews.employee_action_log.show");

    }




}
