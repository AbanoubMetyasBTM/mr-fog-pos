<?php

namespace App\form_builder\filters;


use App\form_builder\FormBuilder;

class ClientFilterBuilder extends FormBuilder
{

    public function __construct()
    {

        $this->select_fields_data["client_type"] = function () {
            return [
                "text"   => ['all', 'wholesaler', 'retailer'],
                "values" => ['', 'wholesaler', 'retailer'],
            ];
        };

        $this->select_fields_data["order_by"] = function () {
            return [
                "text"   => ['all', 'total orders count', 'total orders amount'],
                "values" => ['', 'client_total_orders_count', 'client_total_orders_amount'],
            ];
        };

    }

    public $select_fields = [
        "client_type" => [
            "label_name" => "Client Type",
            "class"      => "form-control",
            "grid"       => "col-md-3",
        ],
        "order_by"    => [
            "label_name" => "Order By",
            "class"      => "form-control",
            "grid"       => "col-md-3",
        ],
    ];

    public $normal_fields = [
        'client_phone', 'client_name',
    ];

    public $normal_fields_custom_attrs = [
        "default_grid"     => "3",
    ];


}
