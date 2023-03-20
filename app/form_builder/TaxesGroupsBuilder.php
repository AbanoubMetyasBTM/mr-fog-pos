<?php

namespace App\form_builder;

class TaxesGroupsBuilder extends FormBuilder
{

    public function __construct()
    {

    }


    public $normal_fields = [
        'group_name',
    ];

    public $normal_fields_custom_attrs = [
        "default_grid"     => "6",
        "default_required" => "required",
        "custom_labels"    => [
            "state_name" => "Group Name",
        ],
    ];

    public $array_fields = [
        'group_taxes' => [
            'label'        => 'Group Taxes',
            'fields'       => ['tax_label', 'tax_percent'],
            "default_grid" => "6",
            "custom_types" => [
                "tax_percent" => "number",
            ],
        ],
    ];



}
