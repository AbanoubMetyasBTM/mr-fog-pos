<?php

namespace App\form_builder;



class NationalHolidaysBuilder extends FormBuilder
{

    public function __construct()
    {
        $this->select_fields_data["country_name"] = function () {

            return [
                "text"   => array_values(listCountryCodes()),
                "values" => array_keys(listCountryCodes()),
            ];

        };

    }

    public $select_fields = [
        "country_name"  => [
            "label_name" => "Country Name",
            "class"      => "form-control select2_search",
            "grid"       => "col-md-12",
        ],

    ];

    public $normal_fields = [
        "holiday_title" ,"holiday_date",
    ];

    public $normal_fields_custom_attrs = [
        "default_required" => "required",
        "default_grid"     => "6",
        "custom_types"     => [
            'holiday_title' => "text",
            'holiday_date'  => "date",
        ],
        "custom_labels"=>[
            'holiday_title' => "Holiday Title",
            'holiday_date'  => "Holiday Date",
        ],

    ];





}
