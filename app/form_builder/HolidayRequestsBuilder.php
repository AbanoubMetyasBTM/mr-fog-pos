<?php

namespace App\form_builder;



class HolidayRequestsBuilder extends FormBuilder
{

    public function __construct()
    {
    }


    public $normal_fields = [
        "req_title", "req_date", "req_desc"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_types"     => [
            'req_title'       => "text",
            'req_desc'        => "textarea",
            'req_date'        => "date",
        ],
        "custom_labels"=>[
            'req_title' => "Request Title",
            'req_desc'  => "Request Description",
            'req_date'  => "Request Date",
        ],
        "custom_grid"=>[
            'req_desc'  => "12",
        ],

    ];





}
