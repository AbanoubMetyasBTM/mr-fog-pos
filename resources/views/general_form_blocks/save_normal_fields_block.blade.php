<?php
    /** @var App\form_builder\FormBuilder $builder */
    /** @var Eloquent $data_object */
?>

<?php
    $normal_fields=$builder->normal_fields;
    $custom_attrs=$builder->normal_fields_custom_attrs;


    $attrs = generate_default_array_inputs_html(
        $normal_fields,
        $data_object,
        "yes",
        isset($custom_attrs["default_required"])?$custom_attrs["default_required"]:"",
        isset($custom_attrs["default_grid"])?$custom_attrs["default_grid"]:"12"
    );

    foreach ($normal_fields as $field){
        $attrs[0][$field]=capitalize_string($field);
    }

    $custom_attrs_fields=[
        "custom_labels"=>"0",
        "custom_required"=>"2",
        "custom_types"=>"3",
        "custom_classes"=>"5",
        "custom_grid"=>"6"
    ];

    foreach ($custom_attrs_fields as $custom_attrs_key=>$custom_attrs_field){

        if(!isset($custom_attrs[$custom_attrs_key]))continue;

        foreach ($custom_attrs[$custom_attrs_key] as $cust_key=>$cust_val){
            $attrs[$custom_attrs_field][$cust_key]=$cust_val;
        }
    }

    echo generate_inputs_html_take_attrs($attrs);
?>
