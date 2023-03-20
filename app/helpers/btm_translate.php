<?php

function getAdminCurrentLangId(){
    return session('admin_select_lang_id', config("default_language.main_lang_id"));
}

//front translate field
function front_tf($obj)
{

    $lang_id = config("default_language.primary_lang_title");
    return show_translate_field_json($obj, $lang_id);

}

function show_translate_field($obj, $field, $lang_id = null)
{

    if ($lang_id == null) {
        $lang_id = config("default_language.primary_lang_title");
    }

    if (!isset($obj->{$field})) return "";

    $field_obj = $obj->{$field};
    if (!is_object($field_obj)) {
        $field_obj = json_decode($obj->{$field});
    }

    if (!is_object($field_obj)) return $obj->{$field};

    if ($lang_id == "first") {
        return collect($field_obj)->first();
    }

    if (!isset($field_obj->{$lang_id})) return $obj->{$field};

    return $field_obj->{$lang_id};
}

function show_translate_field_json($json, $lang_id = null)
{

    if ($lang_id == null) {
        $lang_id = config("default_language.primary_lang_title");
    }

    $text_json = $json;
    if (!is_object($json)) {
        $json = json_decode($json);
    }

    if (is_array($json) && count($json) == 0) return "";
    if (!is_object($json)) return $text_json;
    if (!isset($json->{$lang_id})) return "";

    return $json->{$lang_id};

}
