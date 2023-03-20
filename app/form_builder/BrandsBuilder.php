<?php

namespace App\form_builder;



class BrandsBuilder extends FormBuilder
{

    public $translate_fields = [
        "brand_name"
    ];

    public $cust_translate_fields_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
    ];

    public  $img_fields=[
        "brand_img_obj"=>[
            "width"=>"220",
            "height"=>"220",
            "need_alt_title"=>"No",
            "display_label"=>"Upload brand image",
        ],
    ];


}
