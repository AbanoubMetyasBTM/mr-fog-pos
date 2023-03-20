<?php

namespace App\form_builder;

class AdminsBuilder extends FormBuilder
{

    public function __construct($item_id = null)
    {

        if ($item_id != null) {

            $this->normal_fields_custom_attrs["custom_labels"]["password"] .=
                " (add password if you want to change the old password)";

        }

        $this->select_fields_data["is_active"] = function () {

            return [
                "text"   => ["yes", "no"],
                "values" => ['1', '0'],
            ];

        };


    }

    public $select_fields = [
        "is_active" => [
            "label_name" => "active ?",
            "class"      => "form-control",
            "grid"       => "col-md-6",
        ],
    ];

    public $normal_fields = [
        'full_name', 'email', 'password'
    ];

    public $normal_fields_custom_attrs = [

        "default_grid"    => "6",
        "custom_types"    => [
            "email"    => "email",
            "password" => "password",
        ],
        "custom_labels"    => [
            "email"    => "Email",
            "password" => "Password",
        ],
        "custom_required" => [
            "email" => "required",
        ],

    ];

    public $img_fields = [
        "logo_img_obj" => [
            "width"         => "50",
            "height"        => "50",
            "display_label" => "Image",
        ],
    ];


}
