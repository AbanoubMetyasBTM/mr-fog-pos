<?php

namespace App\form_builder;



class DelayEarlyRequestsBuilder extends FormBuilder
{

    public function __construct()
    {
    }


    public $normal_fields = [
        "req_title", "req_date", "req_wanted_time", "req_desc"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_required"     => [
            'req_wanted_time' => 'required max="8"',
        ],
        "custom_types"     => [
            'req_title'       => "text",
            'req_desc'        => "textarea",
            'req_date'        => "date",
            'req_wanted_time' => 'number',
        ],
        "custom_labels"=>[
            'req_title'       => "Request Title",
            'req_desc'        => "Request Description",
            'req_date'        => "Request Date",
            'req_wanted_time' => "Request Wanted Time (Hours)",
        ],
        "custom_grid"=>[
            'req_desc'  => "12",
        ],

    ];





}
