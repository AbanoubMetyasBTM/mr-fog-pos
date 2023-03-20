<?php


namespace App\form_builder;


class FormBuilder
{
    /** @var array $required_fields additional required fields , if you have select fields or something else and you want to mak it required you should add it  */
    public $required_fields=[];

    public $normal_fields = [];
    public $normal_fields_custom_attrs = [];
    public $select_fields = [];
    public $select_fields_data = [];
    public $translate_fields = [];
    public $cust_translate_fields_attrs = [];
    public $json_fields = [];
    public $img_fields = [];
    public $slider_fields = [];
    public $array_fields=[];


    /**

        function __construct()
        {

            $this->select_fields_data["parent_id"] = function () {
                $conds           = [];
                $conds[]         = ["categories.parent_id", "=", "0"];
                    $all_panret_cats = categories_m::getData([
                    "cond" => $conds
                ]);

                return [
                "text"   => array_merge(["اساسي"], $all_panret_cats->pluck("cat_name")->all()),
                "values" => array_merge(["0"], $all_panret_cats->pluck("cat_id")->all()),
                ];

            };
        }

        public $select_fields = [
            "parent_id" => [
                "label_name" => "اساسي او فرعي",
                "class"      => "form-control select_2_primary",
                "grid"       => "col-md-6",
            ],
        ];
        public  $normal_fields=[
            'pro_price', 'pro_discount_amount',
        ];

        public  $normal_fields_custom_attrs=[
            "default_required"=>"",
            "default_grid"=>"6",
            "custom_labels"=>[
                "pro_price"=>"Product Price - USD $",
                "pro_discount_amount"=>"Discount Amount"
            ],
            "custom_required"=>[],
            "custom_types"=>[
                'pro_price'=>"number",
                'pro_discount_amount'=>"number",
            ],
            "custom_classes"=>[],
            "custom_grid"=>[],
        ];

        public  $translate_fields=[
            'pro_name', 'pro_short_desc', 'pro_desc',
            'pro_meta_title', 'pro_meta_desc', 'pro_meta_keywords',
        ];

        public  $cust_translate_fields_attrs=[
            "default_required"=>"required",
            "default_grid"=>"4",
            "custom_types"=>[
                'pro_short_desc'=>"textarea",
                'pro_desc'=>"textarea",
                'pro_meta_title'=>"textarea",
                'pro_meta_desc'=>"textarea",
                'pro_meta_keywords'=>"textarea",
            ],
            "custom_classes"=>[
                'pro_desc'=>"form-control ckeditor",
            ],
            "custom_grid"=>[
                'pro_name'=>"6",
                'pro_short_desc'=>"6",
                'pro_desc'=>"12",
            ],

            "custom_labels"    => [
                "city_name" => "name here",
            ],
        ];

        public  $json_fields=[
            'cat_id',
        ];

        public  $img_fields=[
            "small_img_obj"=>[
                "width"=>"220",
                "height"=>"220",
                "need_alt_title"=>"yes",
                "display_label"=>"Upload product image",
            ],
        ];

        public  $slider_fields=[
            "pro_slider"=>[
                "imgs_limit"    => 10,
                "width"=>"0",
                "height"=>"0",
                "need_alt_title"=>"yes",
                "display_label"=>"Product slider",
                "additional_inputs"     => [
                    "fields" => ["item_title","item_short_desc"],
                ],
            ],
        ];

        public $array_fields = [

            'product_prices' => [
                'label'         => 'اﻻسعار',
                'fields'        => ['product_price_trans_label', 'product_price_value', 'product_price_discount_value'],
                "default_grid"  => "3",
                "custom_labels" => [
                    "product_price_trans_label"    => "عنوان سعر المنتج",
                    "product_price_value"          => "سعر المنتج",
                    "product_price_discount_value" => "قيمة الخصم",
                ],
                "custom_types"  => [
                    "product_price_value"          => "number",
                    "product_price_discount_value" => "number",
                ],
            ],
            'test_arr' => [
                'label'         => 'test label',
                'fields'        => ['test_arr_1', 'test_arr_2', 'test_arr_3'],
                "default_grid"  => "4",
            ]

        ];
     **/

}
