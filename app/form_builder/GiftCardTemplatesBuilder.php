<?php

namespace App\form_builder;

class GiftCardTemplatesBuilder extends FormBuilder
{

    public function __construct()
    {

        $this->select_fields_data["is_active"] = function () {
            return [
                "text"   => ["yes", "no"],
                "values" => [1, 0],
            ];
        };

    }

    public $select_fields = [
        "is_active" => [
            "label_name" => "Is Active?",
            "class"      => "form-control",
            "grid"       => "col-md-6",
        ],
    ];

    public $normal_fields = [
        "template_title",
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
    ];

    public $img_fields = [
        "template_bg_img_obj" => [
            "need_alt_title" => "yes",
            "display_label"  => "Upload Background image",
        ],
    ];


}
