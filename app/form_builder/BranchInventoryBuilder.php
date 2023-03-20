<?php

namespace App\form_builder;


use App\models\branch\branches_m;
use App\models\inventory\inventories_m;

class BranchInventoryBuilder extends FormBuilder
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

        $this->select_fields_data["inventory_id"] = function () {

            $allBranches = inventories_m::getAllInventories();
            return [
                "text"   => $allBranches->pluck("inv_name")->all(),
                "values" => $allBranches->pluck("inv_id")->all(),
            ];
        };

    }

    public $select_fields = [
        "branch_id"  => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],
        "inventory_id" => [
            "label_name" => "Inventory Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],
    ];

    public  $normal_fields=[
        'is_main_inventory'
    ];

    public  $normal_fields_custom_attrs=[
        "default_required"=>"",
        "default_grid"=>"4",
        "custom_labels"=>[
            "is_main_inventory"=> "Is Main Inventory"
        ],
        "custom_required"=>[],
        "custom_types"=>[
            'is_main_inventory'=>"checkbox",
        ],
        "custom_classes"=>[],
        "custom_grid"=>[],
    ];
}
