<?php

namespace App\form_builder;



use App\models\branch\branches_m;

class EmployeesBuilder extends FormBuilder
{

    public function __construct($branchId = null)
    {

        $this->select_fields_data["user_role"] = function () {
            $currentBranchId = \Session::get("current_branch_id");

            if ($currentBranchId != null) {
                return [
                    "text"   => ['Employee'],
                    "values" => ['employee'],
                ];
            }

            return [
                "text"   => ['Employee', 'Branch Admin', 'Admin'],
                "values" => ['employee', 'branch_admin', 'admin'],
            ];
        };

        $this->select_fields_data["branch_id"] = function () use ($branchId) {

            $allBranches = branches_m::getAllBranchesOrCurrentBranchOnly();
            if ($branchId != null) {
                $allBranches = $allBranches->where("branch_id", $branchId);
            }

            return [
                "text"   => $allBranches->pluck("branch_name")->all(),
                "values" => $allBranches->pluck("branch_id")->all(),
            ];
        };

        $this->select_fields_data["is_active"] = function () {

            return [
                "text"   => ['Active', 'Not Active'],
                "values" => ['1', '0'],
            ];
        };

        $this->select_fields_data["employee_working_days"] = function () {

            return [
                "text"   => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                "values" => ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ];
        };

    }

    public $select_fields = [
        "user_role"  => [
            "label_name" => "Employee Role",
            "class"      => "form-control",
            "grid"       => "col-md-4",
        ],
        "branch_id"  => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],
        "is_active"  => [
            "label_name" => "Is Active",
            "class"      => "form-control",
            "grid"       => "col-md-4",
        ],

        "employee_working_days"  => [
            "label_name" => "Working Days",
            "class"      => "form-control select2",
            "grid"       => "col-md-6",
            "multiple"   => "multiple"
        ],

    ];

    public $normal_fields = [
        'full_name', 'email', 'password', 'phone_code' ,'phone', 'hour_price',
        'employee_required_working_hours_per_day', 'employee_should_start_work_at',
        'employee_should_end_work_at', 'employee_overtime_hour_rate',
        'employee_vacation_hour_rate', 'employee_sick_vacation_max_requests',
        'employee_vacation_max_requests', 'employee_delay_requests_max_requests',
        'employee_early_requests_max_requests'
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_labels"=>[
            "hour_price"                              => "Hour Price",
            "employee_required_working_hours_per_day" => "Required Working Hours Per Day",
            "employee_overtime_hour_rate"             => "Overtime Hour Rate",
            "employee_vacation_hour_rate"             => "Vacation Hour Rate",
            "employee_should_start_work_at"           => "Work Start At",
            "employee_should_end_work_at"             => "Work End At",
            "employee_sick_vacation_max_requests"     => "Sick Vacation Max Requests",
            "employee_delay_requests_max_requests"    => "Delay Request Max Requests",
            "employee_early_requests_max_requests"    => "Early Request Max Requests",
            "employee_vacation_max_requests"          => "Vacation Max Requests",
        ],
        "custom_required"=>[
            "password" => ""
        ],
        "custom_types"=>[
            "email"                                => "email",
            "password"                             => "password",
            "employee_should_start_work_at"        => "time",
            "employee_should_end_work_at"          => "time",
            "hour_price"                           => "number",
            "employee_overtime_hour_rate"          => "number",
            "employee_vacation_hour_rate"          => "number",
            "employee_sick_vacation_max_requests"  => "number",
            "employee_delay_requests_max_requests" => "number",
            "employee_early_requests_max_requests" => "number",
            "employee_vacation_max_requests"       => "number",
        ],
    ];


    public  $img_fields=[
        "logo_img_obj"=>[
            "width"=>"220",
            "height"=>"220",
            "need_alt_title"=>"No",
            "display_label"=>"Upload Employee Image",
        ],
    ];





}
