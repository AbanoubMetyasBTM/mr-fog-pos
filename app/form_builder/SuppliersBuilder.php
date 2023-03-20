<?php

namespace App\form_builder;


use App\models\currencies_m;

class SuppliersBuilder extends FormBuilder
{

    public function __construct($item_id = null)
    {
        $this->select_fields_data["sup_currency"] = function () {

            $allCurrencies = currencies_m::getAllActiveCurrencies();

            return [
                "text"   => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
                "values" => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
            ];

        };

        if ($item_id != null) {
            unset($this->select_fields["sup_currency"]);

            $this->normal_fields_custom_attrs["default_grid"] = "4";
        }

    }

    public $select_fields = [
        "sup_currency" => [
            "label_name" => "Select Currency",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-6",
        ],
    ];


    public $normal_fields = [
        "sup_name", "sup_phone", "sup_company"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_labels"    => [
            "client_name"  => "Supplier Name",
            "client_email" => "Supplier Email",
            "client_phone" => "Supplier Phone"
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
