<?php

namespace App\form_builder;

use App\models\branch\branches_m;
use App\models\taxes_groups_m;

class ClientsBuilder extends FormBuilder
{

    public function __construct()
    {

        $this->select_fields_data["tax_group_id"] = function () {

            $allStates = taxes_groups_m::getAllTaxesGroups();

            return [
                "text"   => array_merge(["None"], $allStates->pluck("group_name")->all()),
                "values" => array_merge([""], $allStates->pluck("group_id")->all()),
            ];

        };

        $this->select_fields_data["branch_id"] = function () {

            $allBranches = branches_m::getAllBranchesOrCurrentBranchOnly();

            return [
                "text"   => $allBranches->pluck("branch_name")->all(),
                "values" => $allBranches->pluck("branch_id")->all(),
            ];

        };

        $this->select_fields_data["client_type"] = function () {

            return [
                "text"   => array_merge(["Retailer"], ["Wholesaler"]),
                "values" => array_merge(["retailer"], ["wholesaler"]),
            ];

        };
    }


    public $select_fields = [
        "client_type"  => [
            "label_name" => "Client Type",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],
        "tax_group_id" => [
            "label_name" => "Select Taxes",
            "class"      => "form-control",
            "grid"       => "col-md-4",
            "required"   => "required"
        ],

        "branch_id" => [
            "label_name" => "Branch Name",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],
    ];

    public $normal_fields = [
        "client_name", "client_email", "client_phone"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_labels"    => [
            "client_name"  => "Client Name",
            "client_email" => "Client Email",
            "client_phone" => "Client Phone"
        ],
        "custom_required"  => [],
        "custom_types"     => [
            'client_name'  => "text",
            'client_email' => "email",
            'client_phone' => "text"
        ],
        "custom_classes"   => [],
        "custom_grid"      => [],
    ];


}
