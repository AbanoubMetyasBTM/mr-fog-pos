<?php

namespace App\Http\Controllers\admin;

use App\form_builder\EmployeesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\client\clients_orders_m;
use App\models\coupon\coupons_m;
use App\models\employee\employee_details_m;
use App\models\supplier\suppliers_orders_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeesController extends AdminBaseController
{

    use CrudTrait;

    /** @var employee_details_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employees");
        $this->modelClass   = employee_details_m::class;
        $this->viewSegment  = "employees";
        $this->routeSegment = "employees";
        $this->builderObj   = new EmployeesBuilder();
        $this->primaryKey   = "id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/employees", "show_action");
        $this->data["request_data"]  = (object)$request->all();
        $conds                       = $request->all();
        $conds['paginate']           = 50;
        $this->data["results"]       = $this->modelClass::getEmployeesOrOrSpecificBranch($conds);
        $this->data["branches"]      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["all_employees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }


    #region save
    public function customValidation(Request $request, $item_id = null)
    {
        $rules_itself             = [];
        $rules_values["password"] = $request->get("password");

        if ($item_id == null) {
            $rules_itself["password"] = "required|string";
        }

        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/employees", $item_id == null ? "add_action" : "edit_action");
    }

    public function getEditObj(Request $request, $item_id)
    {
        $itemObj = employee_details_m::getEmployees([],$item_id);

        if (
            $this->branch_id != null &&
            $itemObj->user_role == "branch_admin"
        ) {
            abort(404);
            die();
        }

        if (
            $this->branch_id!=null &&
            $itemObj->branch_id!=$this->branch_id
        ){
            abort(404);
            die();
        }

        return $this->modelClass::getEmployees([], $item_id);
    }

    private function handelBasicDataOfEmployee(Request $request)
    {
        $fullName               = explode(' ', $request['full_name']);
        $userData               = $request->all();
        $userData['first_name'] = $fullName[0];
        $userData['last_name']  = $fullName[1];
        $userData['user_type']  = 'employee';


        if (isset($request['password']) && !empty($request['password'])) {
            $userData['password']            = bcrypt($request['password']);
            $userData['password_changed_at'] = now();
        }

        return $userData;
    }

    public function beforeSaveRow(Request $request)
    {
        if ($this->branch_id != null) {
            $request["user_role"] = "employee";
            $request["branch_id"] = $this->branch_id;
        }

        return $request;
    }

    public function beforeAddNewRow(Request $request)
    {
        // create user_tbl_row
        $userData = $this->handelBasicDataOfEmployee($request);
        $userObj  = User::create($userData);

        // create user_enc_id
        $userEncId = md5($userObj->user_id);
        User::where('user_id', '=', $userObj->user_id)->
        update(array('user_enc_id' => $userEncId));

        $request->request->add(['employee_id' => $userObj->user_id]);
        return $request;
    }

    public function beforeUpdateRow(Request $request)
    {
        $item_id = $request->get('emp_id');
        $itemObj = employee_details_m::getEmployeesOrOrSpecificBranch([], $item_id);

        if (
            $this->branch_id != null &&
            $itemObj->branch_id != $this->branch_id
        ) {
            abort(404);
            die();
        }

        $userData = $this->handelBasicDataOfEmployee($request);

        User::find($request['emp_id'])->update($userData);
        return $request;
    }
    #endregion

    private function handleEmployeeData($employeeObj): array
    {
        $data['full_name']                               = $employeeObj->full_name;
        $data['branch_name']                             = $employeeObj->branch_name;
        $data['email']                                   = $employeeObj->email;
        $data['phone_code']                              = $employeeObj->phone_code;
        $data['phone']                                   = $employeeObj->phone;
        $data['employee_working_days']                   = $employeeObj->employee_working_days;
        $data['employee_required_working_hours_per_day'] = $employeeObj->employee_required_working_hours_per_day;
        $data['employee_should_start_work_at']           = $employeeObj->employee_should_start_work_at;
        $data['employee_should_end_work_at']             = $employeeObj->employee_should_end_work_at;
        $data['create_order_pin_number']                 = $employeeObj->create_order_pin_number;
        $data['hour_price']                         = $employeeObj->hour_price;
        $data['employee_overtime_hour_rate']             = $employeeObj->employee_overtime_hour_rate;
        $data['employee_vacation_hour_rate']             = $employeeObj->employee_vacation_hour_rate;
        $data['employee_sick_vacation_max_requests']        = $employeeObj->employee_sick_vacation_max_requests;
        $data['employee_delay_requests_max_requests']    = $employeeObj->employee_delay_requests_max_requests;
        $data['employee_early_requests_max_requests']    = $employeeObj->employee_early_requests_max_requests;
        $data['employee_vacation_max_requests']          = $employeeObj->employee_vacation_max_requests;
        $data['is_active']                               = $employeeObj->is_active == 1 ? 'true': 'false';
        $data['created_at']                              = Carbon::parse($employeeObj->created_at)->format('Y-m-d H:i:s');

        return $data;
    }

    public function getEmployeeData(Request $request)
    {
        havePermissionOrRedirect("admin/employees", "show_action");

        $employeeObj = employee_details_m::getEmployees([], $request['item_id']);
        $data        = $this->handleEmployeeData($employeeObj);

        if (
            $this->branch_id != null &&
            (empty($data) || $employeeObj->branch_id != $this->branch_id)
        ){
            abort(404);
            die();
        }

        return $data;
    }

    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/employees", "delete_action");

        $item_id = $request->get('item_id');
        $itemObj = employee_details_m::getEmployees([], $item_id);

        if ($this->branch_id!=null && (is_null($itemObj) || $itemObj->branch_id!=$this->branch_id)){
            abort(404);
            die();
        }
    }

    public function delete(Request $request)
    {
        $this->beforeDeleteRow($request);
        $empDetailsObj = employee_details_m::findOrFail($request->get('item_id'));

        // check if employee has supplier orders && check if employee has client orders
        $checkEmpHasSuppliersOrders = suppliers_orders_m::checkIfEmployeeHasOrders($empDetailsObj->employee_id);
        $checkEmpHasClientsOrders   = clients_orders_m::checkIfEmployeeHasOrders($empDetailsObj->employee_id);

        if ($checkEmpHasSuppliersOrders == false && $checkEmpHasClientsOrders == false) {
            User::destroy($empDetailsObj->employee_id);
            $this->general_remove_item($request, $this->modelClass);
        }
        else {
            $output["msg"] = "Can not delete this employee because he has orders";
            return json_encode($output);
        }

    }


}
