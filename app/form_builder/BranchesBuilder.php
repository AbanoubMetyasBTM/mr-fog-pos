<?php

namespace App\form_builder;


use App\models\currencies_m;
use App\models\taxes_groups_m;

class BranchesBuilder extends FormBuilder
{

    public function __construct($item_id = null)
    {

        $this->select_fields_data["tax_group_id"] = function () {

            $allStates = taxes_groups_m::getAllTaxesGroups();

            return [
                "text"   => $allStates->pluck("group_name")->all(),
                "values" => $allStates->pluck("group_id")->all(),
            ];

        };


        $this->select_fields_data["branch_country"] = function () {

            return [
                "text"   => array_values(listCountryCodes()),
                "values" => array_keys(listCountryCodes()),
            ];

        };

        $this->select_fields_data["branch_currency"] = function () {

            $allCurrencies = currencies_m::getAllActiveCurrencies();

            return [
                "text"   => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
                "values" => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
            ];

        };

        $this->select_fields_data["branch_timezone"] = function () {

            return [
                "text"   => \StaticData\TimeZones::$timezones_arr,
                "values" => \StaticData\TimeZones::$timezones_arr,
            ];

        };

        $this->select_fields_data["first_day_of_the_week"] = function () {
            return [
                "text"   => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                "values" => ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ];
        };

        if ($item_id != null) {
            unset($this->select_fields["branch_currency"]);

            $this->select_fields["branch_country"]["grid"]        = "col-md-3";
            $this->select_fields["branch_timezone"]["grid"]       = "col-md-3";
            $this->select_fields["first_day_of_the_week"]["grid"] = "col-md-3";
            $this->normal_fields_custom_attrs["default_grid"]     = "6";
        }


    }

    public $select_fields = [
        "branch_country"  => [
            "label_name" => "Branch Country",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-3",
        ],
        "tax_group_id" => [
            "label_name" => "Select Taxes",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-3",
            "required"   => "required"
        ],
        "branch_currency" => [
            "label_name" => "Select Currency",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-3",
        ],
        "branch_timezone" => [
            "label_name" => "Select TimeZone",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-3",
        ],
        "first_day_of_the_week" => [
            "label_name" => "Select First Day Of The Week",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-4",
        ],
    ];

    public $normal_fields = [
        "branch_name", "return_policy_days"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"     => [
            "return_policy_days" => "number"
        ],
    ];


    public $img_fields = [
        "branch_img_obj" => [
            "display_label" => "Upload Branch Image",
        ],
    ];


}
