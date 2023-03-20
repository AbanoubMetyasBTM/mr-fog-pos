<?php

namespace App\form_builder;


class PagesBuilder extends FormBuilder
{

    public function __construct()
    {

        $this->select_fields_data["hide_page"] = function () {

            return [
                "text"   => ["no", "yes"],
                "values" => ["0", "1"],
            ];

        };

        $this->select_fields_data["show_page_on_header_menu"] = function () {

            return [
                "text"   => ["no", "yes"],
                "values" => ["0", "1"],
            ];

        };

        $this->select_fields_data["show_page_on_footer_menu"] = function () {

            return [
                "text"   => ["no", "yes"],
                "values" => ["0", "1"],
            ];

        };


    }

    public $select_fields = [
        "hide_page"                => [
            "label_name" => "Hide Page?",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],
        "show_page_on_header_menu" => [
            "label_name" => "Show Page At Header?",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],
        "show_page_on_footer_menu" => [
            "label_name" => "Show Page At Footer?",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-4",
        ],

    ];

    public $normal_fields = [
        'page_title', 'page_slug',
        'page_short_desc',
        'page_body_1',
        'page_body_2',
        'page_general_meta',
        'page_meta_title', 'page_meta_desc', 'page_meta_keywords',
    ];

    public $normal_fields_custom_attrs = [

        "default_grid"     => "6",
        "default_required" => "required",
        "custom_types"     => [
            "page_short_desc"    => "textarea",
            "page_body_1"        => "textarea",
            "page_body_2"        => "textarea",
            "page_general_meta"  => "textarea",
            "page_meta_title"    => "textarea",
            "page_meta_desc"     => "textarea",
            "page_meta_keywords" => "textarea",
        ],
        "custom_classes"   => [
            "page_body_1" => " my_ckeditor",
            "page_body_2" => " my_ckeditor",
        ],
        "custom_grid"      => [
            "page_short_desc" => "12",
            "page_body_1"     => "12",
            "page_body_2"     => "12",
        ],
        "custom_required"  => [
            "page_general_meta" => "",
        ],

    ];

    public $img_fields = [
        "page_img_obj" => [
            "display_label"  => "Background Image",
            "need_alt_title" => "yes",
        ],
    ];

    public $slider_fields = [
        "page_slider"   => [
            "display_label" => "Page slider",
        ],
        "page_services" => [
            "display_label"     => "Product Services",
            "additional_inputs" => [
                "fields" => ["item_title", "item_short_desc"],
            ],
        ],

    ];


}
