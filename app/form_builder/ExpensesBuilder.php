<?php

namespace App\form_builder;

use App\models\branch\branches_m;


class ExpensesBuilder extends FormBuilder
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

        $this->select_fields_data["money_type"] = function () {

            return [
                "text"   => ["Cash", "Debit Card", "Credit Card", "Cheque"],
                "values" => ["cash", "debit_card", "credit_card", "cheque"],
            ];
        };

    }

    public $select_fields = [
        "branch_id"  => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],

        "money_type"  => [
            "label_name" => "Money Type",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-4",
        ],


    ];

    public $normal_fields = [
        "transaction_amount", "transaction_notes",
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"=>[
            'transaction_amount'=>"number",
            'transaction_notes' =>"textarea",
        ],
        "custom_grid"=>[
            'transaction_notes'=>"12",
        ],
    ];



}
