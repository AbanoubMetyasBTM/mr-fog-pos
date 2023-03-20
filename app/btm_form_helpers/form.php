<?php

namespace App\btm_form_helpers;


class form
{

    public static function generate_slider_tags(
        $slider_photos = array(),
        $field_name = "",
        $field_label = "",
        $accept = "image/*",
        $need_alt_title = "yes",
        $additional_inputs_arr = array(),
        $show_as_link = false,
        $add_item_label = "add",
        $without_attachment=false
    )
    {

        $field_id = string_safe($field_name) . "_id";
        $class_name = string_safe($field_name) . "_class";

        $data = [
            "field_id" => $field_id,
            "class_name" => $class_name,
            "slider_photos" => $slider_photos,
            "field_name" => $field_name,
            "field_label" => $field_label,
            "accept" => $accept,
            "need_alt_title" => $need_alt_title,
            "additional_inputs_arr" => $additional_inputs_arr,
            "show_as_link" => $show_as_link,
            "add_item_label" => $add_item_label,
            "without_attachment"=>$without_attachment
        ];


        return \View::make("btm_form_helpers.form.generate_slider_tags")->with($data)->render();
    }


    public static function generate_array_input(
        $label_name,
        $field_name,
        $required,
        $type,
        $values,
        $class,
        $add_tiny_mce = "",
        $default_values = array(),
        $data = "",
        $grid
    )
    {

        $data = [
            "label_name"     => $label_name,
            "field_name"     => $field_name,
            "required"       => $required,
            "type"           => $type,
            "values"         => $values,
            "class"          => $class,
            "add_tiny_mce"   => $add_tiny_mce,
            "default_values" => $default_values,
            "data"           => $data,
            "grid"           => $grid,
        ];


        return \View::make("btm_form_helpers.form.generate_array_input")->with($data)->render();
    }

    //save array of objecs
    public static function generate_array_input_v2(
        $label_name,
        $field_name,
        $required,
        $type,
        $values,
        $class,
        $add_tiny_mce = "",
        $default_values = array(),
        $data = "",
        $grid
    )
    {

        $data = [
            "label_name"     => $label_name,
            "field_name"     => $field_name,
            "required"       => $required,
            "type"           => $type,
            "values"         => $values,
            "class"          => $class,
            "add_tiny_mce"   => $add_tiny_mce,
            "default_values" => $default_values,
            "data"           => $data,
            "grid"           => $grid,
        ];


        return \View::make("btm_form_helpers.form.generate_array_input_v2")->with($data)->render();
    }


    public static function generate_self_edit_input(
        $url = "",
        $item_obj,
        $item_primary_col,
        $item_edit_col,
        $modal_path = "",
        $input_type = "text",
        $label = "Click To Edit",
        $func_after_edit=""
    )
    {
        $data = [
            "url"              => $url,
            "item_obj"         => $item_obj,
            "item_primary_col" => $item_primary_col,
            "item_edit_col"    => $item_edit_col,
            "modal_path"       => $modal_path,
            "input_type"       => $input_type,
            "label"            => $label,
            "func_after_edit"  => $func_after_edit,
        ];

        return \View::make("btm_form_helpers.form.generate_self_edit_input")->with($data)->render();

    }


    public static function generate_multi_accepters(
        $accepturl = "",
        $item_obj,
        $item_primary_col,
        $accept_or_refuse_col,
        $model,
        $accepters_data = ["0" => "Refused", "1" => "Accepted"],
        $display_block = false,
        $func_after_edit = ""
    )
    {

        $data = [
            "accepturl"            => $accepturl,
            "item_obj"             => $item_obj,
            "item_primary_col"     => $item_primary_col,
            "accept_or_refuse_col" => $accept_or_refuse_col,
            "model"                => $model,
            "accepters_data"       => $accepters_data,
            "display_block"        => $display_block,
            "func_after_edit"      => $func_after_edit,
        ];

        return \View::make("btm_form_helpers.form.generate_multi_accepters")->with($data)->render();

    }


    public static function generate_radio_btns(
        $input_type = "radio",
        $field_name,
        $label_name,
        $text,
        $values,
        $selected_value = "",
        $class = "",
        $data = "",
        $grid = "col-md-12",
        $hide_label = false,
        $additional_data = "",
        $custom_style = "",
        $item_grid = "",
        $multiple = ""
    )

    {


        $data = [
            "input_type" => $input_type,
            "field_name" => $field_name,
            "label_name" => $label_name,
            "text" => $text,
            "values" => $values,
            "selected_value" => $selected_value,
            "class" => $class,
            "data" => $data,
            "grid" => $grid,
            "hide_label" => $hide_label,
            "additional_data" => $additional_data,
            "custom_style" => $custom_style,
            "item_grid" => $item_grid,
            "multiple" => $multiple,
        ];

        return \View::make("btm_form_helpers.form.generate_radio_btns")->with($data)->render();

    }


    public static function generate_select_tags(
        $field_name,
        $label_name,
        $text,
        $values,
        $selected_value,
        $class = "",
        $multiple = "",
        $required = "",
        $disabled = "",
        $data = "",
        $grid = "col-md-12",
        $hide_label = false,
        $remove_multiple = false,
        $text_is_translate_field=false,
        $lang_id=1
    )
    {
        $data = [
            "field_name" => $field_name,
            "label_name" => $label_name,
            "text" => $text,
            "values" => $values,
            "selected_value" => $selected_value,
            "class" => $class,
            "multiple" => $multiple,
            "required" => $required,
            "disabled" => $disabled,
            "data" => $data,
            "grid" => $grid,
            "hide_label" => $hide_label,
            "remove_multiple" => $remove_multiple,
            "text_is_translate_field" => $text_is_translate_field,
            "lang_id" => $lang_id,
        ];

        return \View::make("btm_form_helpers.form.generate_select_tags")->with($data)->render();

    }


    public static function generate_select_years(
        $already_selected_value = "",
        $earliest_year,
        $class,
        $name,
        $label = "",
        $data = "",
        $grid = "12"
    )
    {
        $data = [
            "already_selected_value" => $already_selected_value,
            "earliest_year" => $earliest_year,
            "class" => $class,
            "name" => $name,
            "label" => $label,
            "data" => $data,
            "grid" => $grid,
        ];

        return \View::make("btm_form_helpers.form.generate_select_years")->with($data)->render();

    }


    public static function generate_inputs_html(
        $labels_name,
        $fields_name,
        $required,
        $type,
        $values,
        $class,
        $grid = ""
    )
    {
        $data = [
            "labels_name" => $labels_name,
            "fields_name" => $fields_name,
            "required" => $required,
            "type" => $type,
            "values" => $values,
            "class" => $class,
            "grid" => $grid,
        ];

        return \View::make("btm_form_helpers.form.generate_inputs_html")->with($data)->render();

    }


    public static function generate_img_tags_for_form(
        $filed_name,
        $filed_label,
        $required_field = "",
        $checkbox_field_name,
        $need_alt_title = "yes",
        $required_alt_title = "",
        $old_path_value = "",
        $old_title_value = "",
        $old_alt_value = "",
        $recomended_size = "",
        $disalbed = "",
        $displayed_img_width = "50",
        $display_label = "",
        $img_obj = "",
        $grid = ""
    )
    {
        $data = [
            "filed_name" => $filed_name,
            "filed_label" => $filed_label,
            "required_field" => $required_field,
            "checkbox_field_name" => $checkbox_field_name,
            "need_alt_title" => $need_alt_title,
            "required_alt_title" => $required_alt_title,
            "old_path_value" => $old_path_value,
            "old_title_value" => $old_title_value,
            "old_alt_value" => $old_alt_value,
            "recomended_size" => $recomended_size,
            "disalbed" => $disalbed,
            "displayed_img_width" => $displayed_img_width,
            "display_label" => $display_label,
            "img_obj" => $img_obj,
            "grid" => $grid,
        ];

        return \View::make("btm_form_helpers.form.generate_img_tags_for_form")->with($data)->render();

    }


    /**
     * generate_depended_selects
     *
     * @return string 2 select elements ,on change of first element
     * second elment change relativley
     *
     * @param string $field_name_1
     * @param string $field_label_1
     * @param string_array $field_text_1 array of first select options text
     * @param string_array $field_values_1 array of first select options values
     * @param string $field_required_1 this field is required or not
     * @param string $field_class_1 elemet classes pls do not forget form-control
     *
     * @param string $field_name_2
     * @param string $field_label_2
     * @param string_array $field_text_2 array of second select options text
     * @param string_array $field_values_2 array of second select options values
     * @param string_array $field_2_depend_values depended values that select2 will change by select 1 values
     * @param string $field_required_2 this field is required or not
     * @param string $field_class_2 elemet classes pls do not forget form-control
     * @param string $field_data_name1 ex:data-fieldname
     * @param string_Array $field_data_values1 data_values_of_first_select
     * @param string $field_data_name2 ex:data-fieldname
     * @param string_Array $field_data_values2 data_values_of_sec_select
     */

    public static function generate_depended_selects(
        $field_name_1, $field_label_1, $field_text_1, $field_values_1, $field_selected_value_1,
        $field_required_1 = "", $field_class_1 = "",
        $field_name_2, $field_label_2, $field_text_2, $field_values_2, $field_selected_value_2,
        $field_2_depend_values, $field_required_2 = "", $field_class_2 = "",
        $field_data_name1 = "", $field_data_values1 = "",
        $field_data_name2 = "", $field_data_values2 = "",
        $data_obj = "")
    {
        $data = [
            "field_name_1"           => $field_name_1,           "field_label_1"                   => $field_label_1,
            "field_text_1"           => $field_text_1,           "field_values_1"                  => $field_values_1,
            "field_selected_value_1" => $field_selected_value_1, "field_required_1"      => $field_required_1,
            "field_class_1"          => $field_class_1,
            "field_name_2"           => $field_name_2,           "field_label_2"                   => $field_label_2,
            "field_text_2"           => $field_text_2,           "field_values_2"                  => $field_values_2,
            "field_selected_value_2" => $field_selected_value_2, "field_2_depend_values" => $field_2_depend_values,
            "field_required_2"       => $field_required_2,       "field_class_2"               => $field_class_2,
            "field_data_name1"       => $field_data_name1,       "field_data_values1"          => $field_data_values1,
            "field_data_name2"       => $field_data_name2,       "field_data_values2"          => $field_data_values2,
            "data_obj"               => $data_obj
        ];

        return \View::make("btm_form_helpers.form.generate_depended_selects")->with($data)->render();

    }


    public static function generate_map_helper($lat , $lng, $do_not_load_js=false){

        $data=[
            "lat"=>$lat,
            "lng"=>$lng,
            "do_not_load_js"=>$do_not_load_js
        ];

        return \View::make("btm_form_helpers.form.generate_map_helper")->with($data)->render();

    }

}
