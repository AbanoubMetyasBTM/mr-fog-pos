<?php

namespace App\form_builder;

use App\models\branch\branches_m;
use App\models\product\product_skus_m;


class ProductPromotionsBuilder extends FormBuilder
{

    public function __construct()
    {


        $this->select_fields_data["promo_branch_id"] = function () {

            $allBranches = branches_m::getAllBranches();
            return [
                "text"   => array_merge(["All Branches"], $allBranches->pluck("branch_name")->all()),
                "values" => array_merge(["0"], $allBranches->pluck("branch_id")->all()),
            ];
        };


        $this->select_fields_data["promo_sku_ids"] = function () {
            $allProductSkus = product_skus_m::getProductSkusWithVariantValues();
            return [
                "text"   => array_merge(['All Products Skus'], $allProductSkus->pluck('product_name')->all()),
                "values" => array_merge(["0"], $allProductSkus->pluck('ps_id')->all())
            ];
        };


    }

    public $select_fields = [
        "promo_branch_id"      => [
            "label_name" => "Branch Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-6",
        ],

        "promo_sku_ids"  => [
            "label_name" => "Products Skus ",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-6",
            "multiple"   => "multiple"
        ],

    ];

    public $normal_fields = [
        "promo_discount_percent" ,"promo_start_at", "promo_end_at"
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "4",
        "custom_types"     => [
            'promo_discount_percent' => "number",
            'promo_start_at'         => "date_time",
            'promo_end_at'           => "date_time",
        ],
        "custom_labels"=>[
            'promo_discount_percent' => "Promotion Discount Percent",
            'promo_start_at'         => "Promotion Start At",
            'promo_end_at'           => "Promotion End At",
        ],

    ];


    public $translate_fields = [
        "promo_title"
    ];


    public $cust_translate_fields_attrs = [
        "default_required" => "required",
        "default_grid"     => "12",
        "custom_labels"    => [
            "promo_title" => "Promotion Title",
        ],
    ];



}
