<?php

namespace App\form_builder;


use App\User;

class EmployeesWarningsBuilder extends FormBuilder
{

    public function __construct()
    {
        $this->select_fields_data["employee_id"] = function () {
            $allBranches = User::getAllUsersWithTypeOrSpecificBranch('employee');
            return [
                "text"   => array_merge($allBranches->pluck("full_name")->all()),
                "values" => array_merge($allBranches->pluck("user_id")->all()),
            ];
        };

        $this->select_fields_data["warning_is_received"] = function () {
            return [
                "text"   => array_merge(['No', 'Yes']),
                "values" => array_merge(["0", "1"]),
            ];
        };

    }

    public $select_fields = [
        "employee_id"        => [
            "label_name" => "Employee Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-6",
        ],

        "warning_is_received" => [
            "label_name" => "Warning Is Received",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-6",
        ],

    ];

    public $normal_fields = [
        "warning_desc"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",

        "custom_labels"=>[
                "warning_desc" => "Warning Description",
        ],
        "custom_types"=>[
            'warning_desc'=>"textarea",
        ],
    ];


    public  $img_fields=[
        "warning_img_obj"=>[
            "width"=>"220",
            "height"=>"220",
            "need_alt_title"=>"No",
            "display_label"=>"Upload Warning Image",
        ],
    ];
}
