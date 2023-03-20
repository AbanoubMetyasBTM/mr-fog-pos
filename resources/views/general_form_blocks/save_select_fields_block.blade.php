<?php
    /** @var App\form_builder\FormBuilder $builder */
    /** @var Eloquent $data_object */
?>

<?php
    $normal_fields = $builder->select_fields;
    $normal_fields_data  = $builder->select_fields_data;

    foreach($normal_fields as $field=>$field_attrs){

        $field_data                = $normal_fields_data[$field]();
        $field_attrs["field_name"] = $field;
        $field_attrs["text"]       = $field_data["text"];
        $field_attrs["values"]     = $field_data["values"];
        $field_attrs["data"]       = $data_object;

        echo generate_select_tags_v2($field_attrs);

    }

?>
