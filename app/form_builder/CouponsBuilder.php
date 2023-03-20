<?php

namespace App\form_builder;

use App\models\branch\branches_m;


class CouponsBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["branch_id"] = function () {

            $allBranches = branches_m::getAllBranchesOrCurrentBranchOnly();

            $allBranchesNames = $allBranches->pluck("branch_name")->all();
            $allBranchesIds = $allBranches->pluck("branch_id")->all();

            if (count($allBranches) > 1){
                $allBranchesNames = array_merge(["All Branches"], $allBranches->pluck("branch_name")->all());
                $allBranchesIds = array_merge(["0"], $allBranches->pluck("branch_id")->all());
            }


            return [
                "text"   => $allBranchesNames,
                "values" => $allBranchesIds,
            ];
        };

        $this->select_fields_data["coupon_code_type"] = function () {
            return [
                "text"   => array_merge(["Value"], ["Percent"]),
                "values" => array_merge(["value"], ["percent"])
            ];
        };

        $this->select_fields_data["coupon_is_active"] = function () {
            return [
                "text"   => array_merge(["Active"], ["Deactivate"]),
                "values" => array_merge(["1"], ["0"])
            ];
        };


    }

    public $select_fields = [
        "branch_id"        => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search branch_id",

            "grid"       => "col-md-6",
        ],
        "coupon_is_active" => [
            "label_name" => "Coupon Activation Status",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-6",
        ],
        "coupon_code_type" => [
            "label_name" => "Coupon Code Type",
            "class"      => "form-control select2_primary coupon_code_type",
            "grid"       => "col-md-6",
        ],

    ];

    public $normal_fields = [
        "coupon_code_value",
        "coupon_code", "coupon_limited_number",
        "coupon_start_date", "coupon_end_date",
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_types"     => [
            'coupon_code_value' => "number",
            'coupon_start_date' => "date_time",
            'coupon_end_date'   => "date_time",
        ],
    ];

    public $translate_fields = [
        "coupon_title"
    ];


    public $cust_translate_fields_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
    ];


}
