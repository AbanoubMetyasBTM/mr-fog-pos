<?php

namespace App\form_builder;

use App\models\branch\branches_m;


class RegistersBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["branch_id"] = function () {

            $allBranches = branches_m::getAllBranchesOrCurrentBranchOnly();
            return [
                "text"   => $allBranches->pluck("branch_name")->all(),
                "values" => $allBranches->pluck("branch_id")->all(),
            ];
        };


    }

    public $select_fields = [
        "branch_id"  => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-6",
        ],
    ];

    public $normal_fields = [
        "register_name"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_labels"=>[
            "register_name"=> "Register Name",
        ],

    ];



}
