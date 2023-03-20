<?php

namespace App\form_builder;

use App\models\currencies_m;

class LoyaltyPointsToMoneyBuilder extends FormBuilder
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
        "points_amount", "reward_money"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"     => [
            'points_amount' => "number",
            'reward_money'  => "number",
        ],
    ];


}
