<?php

namespace App\form_builder;

class CurrenciesBuilder extends FormBuilder
{

    public function __construct()
    {

        $this->select_fields_data["currency_is_active"] = function () {

            return [
                "text"   => ["Yes", "No"],
                "values" => ["1", "0"],
            ];

        };

    }


    public $select_fields = [
        "currency_is_active" => [
            "label_name" => "Currency is Active ?",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],
    ];

    public $normal_fields = [
        'currency_name', 'currency_code'
    ];

    public $normal_fields_custom_attrs = [

        "default_grid"     => "4",
        "default_required" => "required",
        "custom_labels"    => [
            "currency_name" => "Currency Display Name",
            "currency_code" => "Currency Code 3 letters only (ex. EUR)",
        ],

    ];

}
