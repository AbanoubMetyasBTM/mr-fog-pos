<?php

namespace App\form_builder;


use App\models\brands_m;
use App\models\categories_m;

class ProductsSkuBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["use_default_images"] = function () {

            return [
                "text"   => ["No", "Yes"],
                "values" => ["0", "1"],
            ];

        };


    }

    public $select_fields = [
        "use_default_images"   => [
            "label_name" => "Use Default Images?",
            "class"      => "form-control",
            "grid"       => "col-md-12",
        ],
    ];


    public $img_fields = [
        "ps_img_obj" => [
            "display_label" => "Upload image",
        ],
    ];

    public $slider_fields = [
        "ps_slider" => [
            "imgs_limit"    => 10,
            "display_label" => "Slider",
        ],
    ];


}
