<?php

namespace App\form_builder;


use App\models\categories_m;

class CategoriesBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["parent_id"] = function () {

            $allItems = categories_m::getParentCats();

            return [
                "text"   => array_merge(["Parent"], $allItems->pluck("cat_name")->all()),
                "values" => array_merge(["0"], $allItems->pluck("cat_id")->all()),
            ];

        };


    }

    public $select_fields = [
        "parent_id" => [
            "label_name" => "Parent Or Child?",
            "class"      => "form-control select_2_primary",
            "grid"       => "col-md-12",
        ],
    ];

    public $translate_fields = [
        "cat_name"
    ];

    public $cust_translate_fields_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
        "custom_classes"   => [
            'pro_desc' => "form-control ckeditor",
        ],
        "custom_labels" => [
            "cat_name" => "category name",
        ],
    ];



}
