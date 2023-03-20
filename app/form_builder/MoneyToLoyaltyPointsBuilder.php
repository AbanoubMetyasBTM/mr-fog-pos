<?php

namespace App\form_builder;



use App\models\currencies_m;

class MoneyToLoyaltyPointsBuilder extends FormBuilder
{

    public function __construct()
    {
        $this->select_fields_data["money_currency"] = function () {

            $allCurrencies = currencies_m::getAllActiveCurrencies();

            return [
                "text"   => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
                "values" => array_merge(["USD"], $allCurrencies->pluck("currency_code")->all()),
            ];

        };
    }

    public $select_fields = [
        "money_currency" => [
            "label_name" => "Select Currency",
            "class"      => "form-control select2_primary",
            "grid"       => "col-md-4",
        ],

    ];

    public $normal_fields = [
        "money_amount", "reward_points"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"     => [
            'money_amount'  => "number",
            'reward_points' => "number",
        ],
    ];


}
