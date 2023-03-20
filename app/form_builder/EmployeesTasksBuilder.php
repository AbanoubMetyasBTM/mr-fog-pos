<?php

namespace App\form_builder;


use App\User;

class EmployeesTasksBuilder extends FormBuilder
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

    }

    public $select_fields = [
        "employee_id"        => [
            "label_name" => "Employee Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-12",
        ],
    ];

    public $normal_fields = [
        "task_title", "task_deadline", "task_desc"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",

        "custom_labels"=>[
                "task_desc" => "Task Description",
        ],
        "custom_types"=>[
            'task_desc'     => "textarea",
            "task_deadline" => "date_time"
        ],
        "custom_classes"   => [
            'task_desc' => " my_ckeditor",
        ],
        "custom_grid"=>[
            'task_desc' => "12",
        ],
    ];


    public  $slider_fields=[
        "task_slider"=>[
            "imgs_limit"     => 10,
            "width"          => "0",
            "height"         => "0",
            "need_alt_title" => "no",
            "display_label"  => "Task Slider",
        ],
    ];
}
