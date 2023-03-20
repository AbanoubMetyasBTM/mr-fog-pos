<?php

namespace App\btm_form_helpers;

use App\form_builder\FormBuilder;
use Illuminate\Http\Request;

class general_save_form
{

    public static function prepare_fields_before_show(FormBuilder $builder, $data_object)
    {
        $data_object = general_save_form::prepare_translate_fields_before_show(
            $builder,
            $data_object
        );

        $data_object = general_save_form::prepare_json_fields_before_show(
            $builder,
            $data_object
        );

        $data_object = general_save_form::prepare_arr_fields_before_show(
            $builder,
            $data_object
        );

        $data_object = general_save_form::prepare_img_fields_before_show(
            $builder,
            $data_object
        );

        $data_object = general_save_form::prepare_multiple_select_fields_before_show(
            $builder,
            $data_object
        );

        return $data_object;
    }

    public static function prepare_fields_before_save(Request $request, FormBuilder $builder, $all_lang_objs, $data_object)
    {


        $request = general_save_form::prepare_img_fields_before_save(
            $request,
            $builder,
            $data_object
        );

        $request = general_save_form::prepare_number_fields_before_save(
            $request,
            $builder
        );


        $request = general_save_form::prepare_translate_fields_before_save(
            $request,
            $data_object,
            $builder,
            $all_lang_objs
        );

        $request = general_save_form::prepare_json_fields_before_save(
            $request,
            $builder
        );

        $request = general_save_form::prepare_arr_fields_before_save(
            $request,
            $builder,
            $all_lang_objs
        );


        $request = general_save_form::prepare_multiple_select_fields_before_save(
            $request,
            $builder
        );


        return $request;
    }

    public static function prepare_number_fields_before_save($request, FormBuilder $builder)
    {

        if (!isset($builder->normal_fields_custom_attrs["custom_types"])) return $request;

        $normal_fields_custom_attrs = $builder->normal_fields_custom_attrs;

        foreach ($normal_fields_custom_attrs["custom_types"] as $field => $type) {
            if ($type == "number") {
                $request[$field] = $request->get($field);

                if (empty($request[$field])) {
                    $request[$field] = 0;
                }
            }
            elseif ($type == "checkbox") {
                if (empty($request->get($field))) {
                    $request[$field] = "0";
                }
            }
            elseif ($type == "date_time") {
                if (!empty($request->get($field))) {
                    $request[$field] = date("Y-m-d H:i:s", strtotime($request->get($field)));
                }
                else {
                    $request[$field] = null;
                }
            }
            elseif ($type == "date") {
                if (!empty($request->get($field))) {
                    $request[$field] = date("Y-m-d", strtotime($request->get($field)));
                }
                else {
                    $request[$field] = null;
                }
            }

        }

        return $request;
    }

    public static function prepare_translate_fields_before_show(FormBuilder $builder, $data_object)
    {
        foreach ($builder->translate_fields as $translate_field) {
            $data_object->{$translate_field} = json_decode($data_object->{$translate_field});
            if (!is_object($data_object->{$translate_field})) continue;
            foreach ($data_object->{$translate_field} as $key => $value) {
                $data_object->{$translate_field . "_" . $key} = $value;
            }
        }

        return $data_object;
    }

    public static function prepare_translate_fields_before_save(Request $request, $data_object, FormBuilder $builder, $all_lang_objs)
    {
        $translate_fields_data = [];

        foreach ($builder->translate_fields as $translate_field) {

            if(is_object($data_object)){
                $translate_fields_data[$translate_field] = (array)$data_object->{$translate_field};
            }

            foreach ($all_lang_objs as $lang) {
                $translate_fields_data[$translate_field][$lang->lang_title] = $request->get($translate_field . "_" . $lang->lang_title);
            }

            $request[$translate_field] = json_encode($translate_fields_data[$translate_field], JSON_UNESCAPED_UNICODE);
        }

        return $request;
    }


    public static function prepare_json_fields_before_show(FormBuilder $builder, $data_object)
    {
        foreach ($builder->json_fields as $json_field) {
            $data_object->{$json_field} = json_decode($data_object->{$json_field});
            if (!(
                is_object($data_object->{$json_field}) ||
                is_array($data_object->{$json_field})
            )) {
                $data_object->{$json_field} = [];
            }
        }

        return $data_object;
    }

    public static function prepare_arr_fields_before_show(FormBuilder $builder, $data_object)
    {
        foreach ($builder->array_fields as $arr_filed => $json_field) {
            $data_object->{$arr_filed} = json_decode($data_object->{$arr_filed});


            if (!(
                is_object($data_object->{$arr_filed}) ||
                is_array($data_object->{$arr_filed})
            )) {
                $data_object->{$arr_filed} = [];
            }


            foreach ($data_object->{$arr_filed} as $key => $obj) {
                $old_data = (array)$obj;
                foreach ($old_data as $field => $val) {

                    if (strpos($field, "trans") === false) {
                        $obj->{$arr_filed . "_" . $field} = $val;
                    }
                    else {
                        if (!is_object($val)) continue;
                        foreach ($val as $lang => $lang_val) {
                            $obj->{$arr_filed . "_" . $field . "_" . $lang} = $lang_val;
                        }
                    }

                }
            }


        }

        return $data_object;
    }

    public static function prepare_json_fields_before_save(Request $request, FormBuilder $builder)
    {

        foreach ($builder->json_fields as $json_field) {
            $request[$json_field] = json_encode($request[$json_field]);
        }

        return $request;
    }

    private static function initializeObjects(Request $request, $field, $all_lang_objs)
    {

        $objects      = [];
        $field_values = [];

        if (strpos($field, "trans") !== false) {
            foreach ($all_lang_objs as $lang) {
                $field_values = $request->get($field . "_" . $lang->lang_title);
            }
        }
        else {
            $field_values = $request->get($field);
        }

        foreach ($field_values as $key => $value) {
            if (empty($value)) continue;

            $obj       = new \stdClass();
            $obj->id   = $key + 1;
            $obj->id   = "$obj->id";
            $objects[] = $obj;
        }

        return $objects;

    }

    private static function populateObjects(Request $request, $objects, $array_field_key, $field, $all_lang_objs)
    {

        if (count($objects) == 0) {
            $objects = self::initializeObjects($request, $array_field_key . "_" . $field, $all_lang_objs);
        }

        foreach ($objects as $key => $object) {
            if (strpos($field, "trans") !== false) {
                $translate_data = [];
                foreach ($all_lang_objs as $lang) {
                    $translate_data[$lang->lang_title] = $request->get($array_field_key . "_" . $field . "_" . $lang->lang_title)[$key];
                }

                $object->{$field} = $translate_data;
            }
            else {
                $object->{$field} = $request->get($array_field_key . "_" . $field)[$key];
            }

        }


        return $objects;

    }

    public static function prepare_arr_fields_before_save(Request $request, FormBuilder $builder, $all_lang_objs)
    {

        foreach ($builder->array_fields as $array_field_key => $array_field) {

            $objects = [];

            foreach ($array_field["fields"] as $normalField) {
                $objects = self::populateObjects($request, $objects, $array_field_key, $normalField, $all_lang_objs);
            }

            $request[$array_field_key] = json_encode($objects, JSON_UNESCAPED_UNICODE);

        }

        return $request;
    }


    public static function prepare_multiple_select_fields_before_save(Request $request, FormBuilder $builder)
    {

        foreach ($builder->select_fields as $field => $attr) {
            if (!isset($attr["multiple"]) || $attr["multiple"] != "multiple" || $request[$field]==null) continue;

            $request[$field] = json_encode($request[$field]);
        }

        return $request;
    }


    public static function prepare_img_fields_before_show(FormBuilder $builder, $data_object)
    {
        $data_object->old_paths = new \stdClass();

        foreach ($builder->img_fields as $field => $attr) {
            $data_object->old_paths->{$field} = "";

            if (isset($data_object->{$field})) {
                $data_object->{$field} = json_decode($data_object->{$field});
            }

            if (isset($data_object->{$field}) && isset($data_object->{$field}->path)) {
                $data_object->old_paths->{$field} = $data_object->{$field}->path;
            }
        }

        foreach ($builder->slider_fields as $field => $attr) {
            if (isset($data_object->{$field})) {
                $data_object->{$field} = json_decode($data_object->{$field});
            }
        }

        return $data_object;
    }

    public static function prepare_multiple_select_fields_before_show(FormBuilder $builder, $data_object)
    {

        foreach ($builder->select_fields as $field => $attr) {
            if (!isset($attr["multiple"]) || $attr["multiple"] != "multiple") continue;

            $data_object->{$field} = json_decode($data_object->{$field});
        }

        return $data_object;
    }

    public static function prepare_slider_other_fields_before_save(Request $request, $sliderAttrs)
    {

        if(!isset($sliderAttrs["additional_inputs"])){
            return [];
        }

        $other_fields = $sliderAttrs["additional_inputs"]["fields"];

        $new_fields = $request->only($other_fields);

        $old_other_fields = array();
        foreach ($other_fields as $field_key => $field_value) {
            $old_other_fields[] = "edit_" . $field_value;
        }
        $old_fields = $request->only($old_other_fields);


        $all_data = array();
        foreach ($new_fields as $new_field_key => $new_field_val) {

            $new = $new_fields[$new_field_key];

            $old = array();
            if (isset($old_fields["edit_$new_field_key"]) && is_array($old_fields["edit_$new_field_key"])) {
                $old = $old_fields["edit_$new_field_key"];
            }

            if (!is_array($new)) {
                $new = [];
            }
            $all_data["$new_field_key"] = array_merge($old, $new);
        }

        return $all_data;

    }

    public static function prepare_img_fields_before_save(Request $request, FormBuilder $builder, $data_object)
    {

        foreach ($builder->img_fields as $field => $attr) {
            if (!is_object($data_object)) {
                $data_object = new \stdClass();
            }

            if (!isset($data_object->old_paths)) {
                $data_object->old_paths = new \stdClass();
            }

            if (!isset($data_object->old_paths->{$field})) {
                $data_object->old_paths->{$field} = "";
            }

            $request[$field] = image::general_save_img_without_attachment($request, [
                "old_path"             => $data_object->old_paths->{$field},
                "img_file_name"        => $field . "_file",
                "upload_new_img_check" => $request->get($field . "_file_checkbox"),
                "new_title"            => $request->get($field . "_filetitle"),
                "new_alt"              => $request->get($field . "_filealt"),
                "width"                => isset($attr["width"]) ? $attr["width"] : "0",
                "height"               => isset($attr["height"]) ? $attr["height"] : "0",
            ]);

            $request[$field] = json_encode($request[$field]);
        }

        foreach ($builder->slider_fields as $field => $attr) {


            $json_slider_data = json_decode($request->get("json_values_of_slider" . $field . "_file"));

            $request[$field] = image::general_save_slider_without_attachment(
                $request,
                [
                    "img_file_name"         => $field . "_file",
                    "new_title_arr"         => $request->get($field . "_file_title"),
                    "new_alt_arr"           => $request->get($field . "_file_alt"),
                    "json_values_of_slider" => $json_slider_data,
                    "old_title_arr"         => $request->get($field . "_file_edit_title"),
                    "old_alt_arr"           => $request->get($field . "_file_edit_alt"),
                    "return_without_encode" => true,
                    "width"                 => isset($attr["width"]) ? $attr["width"] : "0",
                    "height"                => isset($attr["height"]) ? $attr["height"] : "0",
                ]
            );

            if (isset($attr["imgs_limit"]) && count($request[$field]) > $attr["imgs_limit"]) {

                $request[$field] = array_chunk($request[$field], $attr["imgs_limit"])[0];

            }

            $request[$field] = json_encode([
                "slider_objs"  => $request[$field],
                "other_fields" => self::prepare_slider_other_fields_before_save($request, $attr),
            ]);

        }


        return $request;
    }

    public static function save_normal_fields_block(
        FormBuilder $builder,
        $data_object
    )
    {

        return \View::make("general_form_blocks.save_normal_fields_block", [
            "builder"     => $builder,
            "data_object" => $data_object
        ])->render();
    }

    public static function save_select_fields_block(
        FormBuilder $builder,
        $data_object
    )
    {

        return \View::make("general_form_blocks.save_select_fields_block", [
            "builder"     => $builder,
            "data_object" => $data_object
        ])->render();
    }

    public static function save_translate_fields_block(
        $builder,
        $all_langs,
        $data_object
    )
    {

        return \View::make("general_form_blocks.save_translate_fields_block", [
            "builder"     => $builder,
            "all_langs"   => $all_langs,
            "data_object" => $data_object
        ])->render();
    }

    public static function draw_img_fields($builder, $data_object)
    {
        return \View::make("general_form_blocks.draw_img_fields", [
            "builder"     => $builder,
            "data_object" => $data_object,
        ])->render();
    }

}
