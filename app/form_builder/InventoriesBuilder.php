<?php

namespace App\form_builder;



class InventoriesBuilder extends FormBuilder
{

    public function __construct()
    {

    }


    public $normal_fields = [
        "inv_name"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
        "custom_labels"=>[
            "inv_name"=>"Inventory Name",
        ],
    ];


}
