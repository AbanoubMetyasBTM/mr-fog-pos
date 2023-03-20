<?php

function generate_fields_for_edit_content($fields_arr, $default_field_type = "text")
{
    $return_fields_arr = array();


    foreach ($fields_arr as $key => $field) {
        $return_fields_arr[$field] = array(
            "field_name"  => "$field",
            "field_type"  => "$default_field_type",
            "field_class" => "form-control"
        );
    }

    return $return_fields_arr;
}

function generate_arr_fields_for_edit_content($fileds_arr)
{

    $return_fields = array();
    foreach ($fileds_arr as $key => $field) {
        $return_fields[$field] = array(
            "label_name"   => $field,
            "field_name"   => $field,
            "field_type"   => "text",
            "field_class"  => "form-control",
            "add_tiny_mce" => "no"
        );
    }


    return $return_fields;
}

function generate_imgs_fields_for_edit_content($fields_arr)
{
    $return_fields = array();

    foreach ($fields_arr as $key => $field) {
        $return_fields[$field] = array(
            "field_name"                  => "$field" . "[]",
            "field_name_without_brackets" => "$field",
            "required"                    => "yes",
            "need_alt_title"              => "no",
            "width"                       => "0",
            "height"                      => "0",
        );
    }

    return $return_fields;
}

function generate_slider_fieldes_for_edit_content($fields_arr)
{
    $return_fields = array();
    foreach ($fields_arr as $key => $field) {

        $field_name = $field["field_name"];

        $additional_inputs_arr = "";
        if (isset($field["additional_inputs_arr"])) {
            $additional_inputs_arr = $field["additional_inputs_arr"];
        }


        $return_fields[$field_name] = array(
            "field_name"            => "$field_name",
            "label_name"            => "$field_name",
            "field_id"              => "$field_name" . "_id",
            "accept"                => "image/*",
            "need_alt_title"        => "no",
            "additional_inputs_arr" => $additional_inputs_arr,
            "width"                 => "0",
            "height"                => "0",
            "folder"                => "edit_content_folder"
        );
    }

    return $return_fields;
}

function generate_select_tags_for_edit_content($fields_arr)
{
    $return_fields = array();

    foreach ($fields_arr as $key => $field) {
        $return_fields[$field] = array(
            "field_name"     => "$field",
            "label_name"     => "$field",
            "text"           => array(""),
            "values"         => array(""),
            "selected_value" => array(""),
            "class"          => "form-control",
            "multiple"       => "",
            "required"       => "",
            "disabled"       => ""
        );

    }
    return $return_fields;
}

function generate_default_array_inputs_html($fields_name, $data, $key_in_all_fields = "", $requried = "required", $grid_default_value = 12)
{
    $labels_name = array();
    $required    = array();
    $type        = array();
    $values      = array();
    $class       = array();
    $grid        = array();


    foreach ($fields_name as $key => $value) {

        if ($key_in_all_fields != "") {
            $labels_name[$value] = capitalize_string($value);
            $required[$value]    = $requried;

            if (isset($data->$value)) {
                $values[$value] = $data->$value;
            }
            else {
                $values[$value] = "";
            }
        }
        else {
            $labels_name[] = capitalize_string($value);
            $required[]    = $requried;

            if (isset($data->$value)) {
                $values[] = $data->$value;
            }
            else {
                $values[] = "";
            }
        }


        $type[$value]  = "text";
        $class[$value] = "form-control";
        $grid[$value]  = $grid_default_value;
    }


    return array($labels_name, array_combine($fields_name, $fields_name), $required, $type, $values, $class, $grid);
}

function reformate_arr_without_keys($arr)
{

    $new_arr = array();

    foreach ($arr as $key => $value) {
        $new_arr[] = $value;
    }

    return $new_arr;
}

function generate_img_tags_for_form($filed_name, $filed_label, $required_field = "", $checkbox_field_name, $need_alt_title = "yes", $required_alt_title = "", $old_path_value = "", $old_title_value = "", $old_alt_value = "", $recomended_size = "", $disalbed = "", $displayed_img_width = "50", $display_label = "", $img_obj = "", $grid = "")
{

    return \App\btm_form_helpers\form::generate_img_tags_for_form(
        $filed_name,
        $filed_label,
        $required_field,
        $checkbox_field_name,
        $need_alt_title,
        $required_alt_title,
        $old_path_value,
        $old_title_value,
        $old_alt_value,
        $recomended_size,
        $disalbed,
        $displayed_img_width,
        $display_label,
        $img_obj,
        $grid
    );

}

function getImageObjOfParentObj($parentObj, $imgField)
{
    if (!is_object($parentObj)) {
        return "";
    }
    if (!isset($parentObj->{$imgField})) {
        return "";
    }
    return json_decode($parentObj->{$imgField});
}

function getImagePathOfParentObj($parentObj, $imgField)
{
    $imgFieldData = getImageObjOfParentObj($parentObj, $imgField);
    if (!is_object($imgFieldData) || !isset($imgFieldData->path)) {
        return "";
    }
    return $imgFieldData->path;
}

function generate_img_tags_for_form_v2(array $attrs)
{

    $send_attrs = [
        "field_name"          => $attrs["field_name"],
        "field_label"         => $attrs["field_name"],
        "required_field"      => "",
        "checkbox_field_name" => $attrs["field_name"] . "_checkbox",
        "need_alt_title"      => "",
        "required_alt_title"  => "",
        "old_path_value"      => "",
        "old_title_value"     => "",
        "old_alt_value"       => "",
        "recomended_size"     => "",
        "disalbed"            => "",
        "displayed_img_width" => "50",
        "display_label"       => "",
        "img_obj"             => "",
        "grid"                => "col-md-12",
    ];


    foreach ($attrs as $key => $attr) {
        $send_attrs[$key] = $attr;
    }


    return call_user_func_array("generate_img_tags_for_form", $send_attrs);

}


function generate_inputs_html_take_attrs($attrs)
{
    return \App\btm_form_helpers\form::generate_inputs_html(
        reformate_arr_without_keys($attrs[0]),
        reformate_arr_without_keys($attrs[1]),
        reformate_arr_without_keys($attrs[2]),
        reformate_arr_without_keys($attrs[3]),
        reformate_arr_without_keys($attrs[4]),
        reformate_arr_without_keys($attrs[5]),
        reformate_arr_without_keys($attrs[6])
    );
}

function generate_inputs_html($labels_name, $fields_name, $required, $type, $values, $class, $grid = "")
{

    return \App\btm_form_helpers\form::generate_inputs_html(
        $labels_name,
        $fields_name,
        $required,
        $type,
        $values,
        $class,
        $grid
    );

}

function generate_select_years($already_selected_value = "", $earliest_year, $class, $name, $label = "", $data = "", $grid = "12")
{
    return \App\btm_form_helpers\form::generate_select_years(
        $already_selected_value,
        $earliest_year,
        $class,
        $name,
        $label,
        $data,
        $grid

    );
}

function generate_array_input($label_name, $field_name, $required, $type, $values, $class, $add_tiny_mce = "", $default_values = array(), $data = "", $grid)
{

    return \App\btm_form_helpers\form::generate_array_input(
        $label_name, $field_name, $required, $type, $values, $class, $add_tiny_mce, $default_values, $data, $grid
    );
}

function generate_array_input_v2($label_name, $field_name, $required, $type, $values, $class, $add_tiny_mce = "", $default_values = array(), $data = "", $grid)
{

    return \App\btm_form_helpers\form::generate_array_input_v2(
        $label_name, $field_name, $required, $type, $values, $class, $add_tiny_mce, $default_values, $data, $grid
    );
}


function generate_slider_imgs_tags($slider_photos = array(), $field_name = "", $field_label = "", $field_id = "", $accept = "image/*", $need_alt = "yes", $need_title = "yes", $additional_inputs_arr = array(), $show_as_link = false, $add_item_label = "اضف", $without_attachment = false)
{
    return \App\btm_form_helpers\form::generate_slider_tags(
        $slider_photos,
        $field_name,
        $field_label,
        $accept,
        $need_alt_title = $need_alt,
        $additional_inputs_arr,
        $show_as_link,
        $add_item_label,
        $without_attachment
    );
}

function getSliderObjOfParentObj($parentObj,$sliderField){
    if(!is_object($parentObj)){
        return [];
    }
    if(!isset($parentObj->{$sliderField})){
        return [];
    }
    if(is_array($parentObj->{$sliderField})){
        return $parentObj->{$sliderField};
    }
    return json_decode($parentObj->{$sliderField});
}

function generate_slider_imgs_tags_v2(array $attrs)
{


    $send_attrs = [
        'slider_photos'         => [],
        'field_name'            => $attrs["field_name"],
        'field_label'           => "",
        'field_id'              => $attrs["field_name"] . "_file_id",
        'accept'                => "image/*",
        'need_alt'              => "no",
        'need_title'            => "no",
        'additional_inputs_arr' => [],
        'show_as_link'          => false,
        'add_item_label'        => "add",
        'without_attachment'    => true
    ];


    foreach ($attrs as $key => $attr) {
        $send_attrs[$key] = $attr;
    }

    return call_user_func_array("generate_slider_imgs_tags", $send_attrs);


}


function generate_select_tags($field_name, $label_name, $text, $values, $selected_value, $class = "", $multiple = "", $required = "", $disabled = "", $data = "", $grid = "col-md-12", $hide_label = false, $remove_multiple = false, $text_is_translate_field = false, $lang_id = 1)
{

    return \App\btm_form_helpers\form::generate_select_tags(
        $field_name,
        $label_name,
        $text,
        $values,
        $selected_value,
        $class,
        $multiple,
        $required,
        $disabled,
        $data,
        $grid,
        $hide_label,
        $remove_multiple,
        $text_is_translate_field,
        $lang_id
    );
}

function generate_select_tags_v2($attrs = [])
{

    $send_attrs = [
        "field_name"              => "",
        "label_name"              => "",
        "text"                    => [],
        "values"                  => [],
        "selected_value"          => "",
        "class"                   => "form-control",
        "multiple"                => "",
        "required"                => "",
        "disabled"                => "",
        "data"                    => "",
        "grid"                    => "col-md-12",
        "hide_label"              => false,
        "remove_multiple"         => false,
        "text_is_translate_field" => false,
        "lang_id"                 => config("default_language.primary_lang_title")
    ];


    foreach ($attrs as $key => $attr) {
        $send_attrs[$key] = $attr;
    }

    return call_user_func_array("generate_select_tags", $send_attrs);
}

function generateBTMSelect2($attrs = [])
{

    $send_attrs = [
        "field_name"                => "",
        "label_name"                => "",
        "text"                      => [],
        "values"                    => [],
        "selected_value"            => "",
        "required"                  => "",
        "class"                     => "form-control convert_select_to_btm_select2",
        "data"                      => "",
        "grid"                      => "col-md-12",
    ];

    $select2Fields = [
        "data-class"                           => "",
        "data-placeholder"                     => showContent("general_keywords.search"),
        "data-allow_cache"                     => "false",
        "data-url"                             => "",
        "data-min_search_chars_msg"            => showContent("general_keywords.please_enter") . " CHAR_NUM " . showContent("general_keywords.or_more_characters"),
        "data-min_search_chars"                => "3",
        "data-search_keyword"                  => "q",
        "data-action_type"                     => "GET",
        "data-value_field_name"                => "id",
        "data-text_field_name"                 => "display_text",
        "data-pre_selected_value"              => "",
        "data-pre_selected_text"               => "",
        "data-run_after_select"                => "",
        "data-hide_label"                      => isset($attrs["hide_label"]) ? $attrs["hide_label"] : false,
        "data-append_data_from_dom_by_classes" => ""
    ];

    $select2Fields["data-hide_label"] = (int)$select2Fields["data-hide_label"];

    $send_attrs        = array_merge($send_attrs, $select2Fields);

    $select2FieldsKeys = array_keys($select2Fields);

    foreach ($send_attrs as $key=>$val){
        if (in_array($key, $select2FieldsKeys)) {
            $select2Fields[$key]= " $key='{$send_attrs[$key]}'";
        }
    }

    foreach ($attrs as $key => $attr) {
        $send_attrs[$key] = $attr;

        if (in_array($key, $select2FieldsKeys)) {
            $select2Fields[$key]= " $key='{$send_attrs[$key]}'";
        }
    }

    $send_attrs["required"] = implode(" ",$select2Fields);

    echo generate_select_tags_v2($send_attrs);


}

function generate_radio_btns($input_type = "radio", $field_name, $label_name, $text, $values, $selected_value = "", $class = "", $data = "", $grid = "col-md-12", $hide_label = false, $additional_data = "", $custom_style = "", $item_grid = "", $multiple = "")
{

    return \App\btm_form_helpers\form::generate_radio_btns(
        $input_type,
        $field_name,
        $label_name,
        $text,
        $values,
        $selected_value,
        $class,
        $data,
        $grid,
        $hide_label,
        $additional_data,
        $custom_style,
        $item_grid,
        $multiple
    );


}

function generate_depended_selects($field_name_1, $field_label_1, $field_text_1, $field_values_1, $field_selected_value_1, $field_required_1 = "", $field_class_1 = "", $field_name_2, $field_label_2, $field_text_2, $field_values_2, $field_selected_value_2, $field_2_depend_values, $field_required_2 = "", $field_class_2 = "", $field_data_name1 = "", $field_data_values1 = "", $field_data_name2 = "", $field_data_values2 = "", $data_obj = "")
{
    return \App\btm_form_helpers\form::generate_depended_selects(
        $field_name_1, $field_label_1, $field_text_1, $field_values_1, $field_selected_value_1,
        $field_required_1, $field_class_1,
        $field_name_2, $field_label_2, $field_text_2, $field_values_2, $field_selected_value_2,
        $field_2_depend_values, $field_required_2, $field_class_2,
        $field_data_name1, $field_data_values1,
        $field_data_name2, $field_data_values2,
        $data_obj
    );
}

function generate_multi_accepters($accepturl = "", $item_obj, $item_primary_col, $accept_or_refuse_col, $model, $accepters_data = ["0" => "Refused", "1" => "Accepted"], $display_block = false, $func_after_edit = "")
{

    return \App\btm_form_helpers\form::generate_multi_accepters(
        $accepturl,
        $item_obj,
        $item_primary_col,
        $accept_or_refuse_col,
        $model,
        $accepters_data,
        $display_block,
        $func_after_edit
    );

}

function generate_self_edit_input($url = "", $item_obj, $item_primary_col, $item_edit_col, $modal_path = "", $input_type = "text", $label = "Click To Edit", $func_after_edit = "")
{
    return \App\btm_form_helpers\form::generate_self_edit_input(
        $url,
        $item_obj,
        $item_primary_col,
        $item_edit_col,
        $modal_path,
        $input_type,
        $label,
        $func_after_edit
    );
}

function attrs_divider($attrs, $attrs_length = 7, $dividers_arries)
{

    $new_attrs = [];

    foreach ($dividers_arries as $divider_arr) {
        $new_attrs_item = [];
        foreach ($divider_arr as $field_key => $field) {
            for ($i = 0; $i < $attrs_length; $i++) {
                if (!isset($new_attrs_item[$i])) {
                    $new_attrs_item[$i] = [];
                }

                if(!isset($attrs[$i][$field])){
                    continue;
                }

                $new_attrs_item[$i][$field] = $attrs[$i][$field];

            }
        }
        $new_attrs[] = $new_attrs_item;
    }

    return $new_attrs;
}

function generate_map_helper($lat, $lng, $do_not_load_js = false)
{

    return \App\btm_form_helpers\form::generate_map_helper($lat, $lng, $do_not_load_js);

}

function save_normal_fields_block(
    $model,
    $data_object
)
{
    return \App\btm_form_helpers\general_save_form::save_normal_fields_block(
        $model,
        $data_object
    );
}

function save_select_fields_block(
    $model,
    $data_object
)
{
    return \App\btm_form_helpers\general_save_form::save_select_fields_block(
        $model,
        $data_object
    );
}


function save_translate_fields_block(
    $model,
    $all_langs,
    $data_object
)
{
    return \App\btm_form_helpers\general_save_form::save_translate_fields_block(
        $model,
        $all_langs,
        $data_object
    );
}

function draw_img_fields($model, $data_object)
{
    return \App\btm_form_helpers\general_save_form::draw_img_fields($model, $data_object);
}

