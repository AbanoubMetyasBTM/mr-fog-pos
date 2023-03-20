<?php

namespace App\form_builder;


use App\models\brands_m;
use App\models\categories_m;

class ProductsBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["cat_id"] = function () {

            $allItems = categories_m::getSubCats();

            return [
                "text"   => $allItems->pluck("combined_name")->all(),
                "values" => $allItems->pluck("cat_id")->all(),
            ];

        };

        $this->select_fields_data["brand_id"] = function () {

            $allItems = brands_m::getAllBrands();

            return [
                "text"   => $allItems->pluck("brand_name")->all(),
                "values" => $allItems->pluck("brand_id")->all(),
            ];

        };


    }

    public $select_fields = [
        "cat_id"   => [
            "label_name" => "Select category?",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],
        "brand_id" => [
            "label_name" => "Select Brand?",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-4",
        ],

    ];

    public $normal_fields = [
        "standard_box_quantity"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"     => [
            "standard_box_quantity" => "number",
        ],
        "custom_required"  => [
            "standard_box_quantity" => "required min='1'",
        ],
    ];


    public $translate_fields = [
        "pro_name", "pro_desc"
    ];

    public $cust_translate_fields_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
        "custom_classes"   => [
            'pro_desc' => "form-control ckeditor",
        ],
        "custom_types"     => [
            "pro_desc" => "textarea",
        ],
    ];

    public $img_fields = [
        "pro_img_obj" => [
            "display_label" => "Upload product image",
        ],
    ];

    public $slider_fields = [
        "pro_slider" => [
            "imgs_limit"    => 10,
            "display_label" => "Product slider",
        ],
    ];


}
