<?php

namespace App\btm_form_helpers;

use App\models\generate_site_content_methods_m;
use App\models\site_content_m;

class site_content
{

    public static $data = [];

    /**
     * @param arr_of_str $content_row_title array of content_titles
     * important note the row you can fetch coreectly is the row the saved
     * by general_save_content
     *
     * $slider_imgs_field_arr== $slider_imgs_arr["edit_index_page"]=array("slider1","slider2","slider3")
     *
     */
    public static function general_get_content(
        $lang_title,
        $content_row_title = array(),
        $slider_imgs_field_arr = array()
    )
    {

        foreach ($content_row_title as $key => $title) {

            $cache_data = \Cache::get($title . "_" . $lang_title);

            if ($cache_data != null) {
                self::$data["$title"] = json_decode($cache_data);

                self::cacheSiteContent($title, json_decode($cache_data), $lang_title);

                continue;
            }

            self::$data["$title"] = "";
            $edit_content_row     = site_content_m::getWhere($lang_title, $title);
            if (!is_object($edit_content_row)) {
                continue;
            }
            $edit_content_row = $edit_content_row->content_json;

            $generate_site_content_method = generate_site_content_methods_m::getWhere($title);

            if (!is_object($generate_site_content_method)) {
                continue;
            }

            $generate_site_content_method = $generate_site_content_method->method_requirments;

            //get imgs data
            //check if there is imgs in $edit_content_row
            if (isset($edit_content_row->img_ids) && is_object($edit_content_row->img_ids)) {
                foreach ($edit_content_row->img_ids as $img_key => $img_id) {
                    $img_var_name                    = $img_key;
                    $edit_content_row->$img_var_name = $img_id;

                    if (!is_object($edit_content_row->$img_var_name)) {
                        $edit_content_row->$img_var_name        = new \stdClass();
                        $edit_content_row->$img_var_name->path  = "";
                        $edit_content_row->$img_var_name->title = "";
                        $edit_content_row->$img_var_name->alt   = "";
                    }
                }
            }

            //get slider data

            if (isset($slider_imgs_field_arr["$title"]) && is_array($slider_imgs_field_arr["$title"])) {
                foreach ($slider_imgs_field_arr["$title"] as $key => $slider) {

                    if (!isset($edit_content_row->{$slider})) {
                        continue;
                    }

                    $edit_content_row->{$slider}->imgs = $edit_content_row->{$slider}->img_ids;
                }
            }

            //get selected data
            if (isset($generate_site_content_method->select_fields->fields) && is_array($generate_site_content_method->select_fields->fields)) {
                $select_fields = $generate_site_content_method->select_fields->fields;
                $select_tables = $generate_site_content_method->select_fields->tables;

                foreach ($select_fields as $key => $field) {
                    if (isset($edit_content_row->$field)) {
                        //get field_value,model
                        $field_value = $edit_content_row->$field;
                        $model_name  = $select_tables->$field->model;

                        $edit_content_row->$field = $model_name::find($field_value);
                    }
                }


            }
            //END get selected data

            self::$data["$title"] = $edit_content_row;

            \Cache::put($title . "_" . $lang_title, json_encode($edit_content_row), 60 * 60 * 30);

            self::cacheSiteContent($title, $edit_content_row, $lang_title);

        }//end foreach

        $GLOBALS["site_content"] = [];
        $GLOBALS["site_content"] = array_merge(self::$data, $GLOBALS["site_content"]);

        //return self::$data;
    }

    public static function cacheSiteContent($method_name, $method_data, $lang_title)
    {

        foreach ($method_data as $key => $value) {

            \Cache::forever("{$method_name}.{$key}.{$lang_title}", $value);

        }

    }

    public static function removeCacheSiteContent($method_name, $lang_title)
    {

        self::general_get_content($lang_title, [$method_name]);

        \Cache::forget($method_name . "_" . $lang_title);

        foreach ($GLOBALS["site_content"][$method_name] as $key => $value) {
            \Cache::forget("{$method_name}.{$key}.{$lang_title}", $value);
        }

    }


    public static function createNewMethod($method_name)
    {
        $site_content = generate_site_content_methods_m::getWhere($method_name);

        if (!is_object($site_content)) {
            //create new row
            $site_content = generate_site_content_methods_m::create([
                "method_name"        => $method_name,
                "method_requirments" => [],
                "method_img"         => [
                    "alt"   => "",
                    "title" => "",
                    "path"  => "",
                ],
            ]);
        }

        return $site_content;
    }

    public static function addNormalFieldToMethod($method_name, $field_name, $field_type = "input_fields")
    {

        $site_content       = self::createNewMethod($method_name);
        $method_requirments = $site_content->method_requirments;

        if (!is_object($method_requirments)) {
            $method_requirments = $site_content->method_requirments;
        }

        //it can be a new geneator and has nothing
        //or
        //it could be generator has no normal input fields jsut images or slider

        if (!is_object($method_requirments)) {

            $method_requirments[$field_type]            = new \stdClass();
            $method_requirments[$field_type]->fields    = [$field_name];
            $method_requirments[$field_type]->customize = new \stdClass();

            generate_site_content_methods_m::update($method_name, [
                "method_name"        => $method_name,
                "method_requirments" => $method_requirments,
                "method_img"         => [
                    "alt"   => "",
                    "title" => "",
                    "path"  => "",
                ],
            ]);

            return;
        }

        if (!isset($method_requirments->{$field_type})) {

            $method_requirments->{$field_type}            = new \stdClass();
            $method_requirments->{$field_type}->fields    = [$field_name];
            $method_requirments->{$field_type}->customize = new \stdClass();

            generate_site_content_methods_m::update($method_name, [
                "method_name"        => $method_name,
                "method_requirments" => $method_requirments,
                "method_img"         => [
                    "alt"   => "",
                    "title" => "",
                    "path"  => "",
                ],
            ]);

            return;
        }

        //append to other input fields
        if (isset($method_requirments->{$field_type}) && !in_array($field_name, $method_requirments->{$field_type}->fields)) {

            $method_requirments->{$field_type}->fields[] = $field_name;

            generate_site_content_methods_m::update($method_name, [
                "method_name"        => $method_name,
                "method_requirments" => $method_requirments,
                "method_img"         => [
                    "alt"   => "",
                    "title" => "",
                    "path"  => "",
                ],
            ]);

            return;
        }
    }

    public static function addArrayFieldToMethod($method_name, $setName, $serFields, $defaultValues=[])
    {

        $site_content       = self::createNewMethod($method_name);
        $method_requirments = $site_content->method_requirments;

        if (!is_object($method_requirments)) {
            $method_requirments = $site_content->method_requirments;
        }

        //it can be a new geneator and has nothing
        //or
        //it could be generator has no normal input fields jsut images or slider

        if(!is_object($method_requirments)){
            $method_requirments = new \stdClass();
        }

        if (!isset($method_requirments->arr_fields)) {
            $method_requirments->arr_fields            = new \stdClass();
            $method_requirments->arr_fields->fields    = new \stdClass();
            $method_requirments->arr_fields->customize = new \stdClass();
        }

        if(isset($method_requirments->arr_fields->fields->{$setName})){
            return;
        }


        $method_requirments->arr_fields->fields->{$setName}    = new \stdClass();
        $method_requirments->arr_fields->fields->{$setName}    = $serFields;
        $method_requirments->arr_fields->customize->{$setName} = new \stdClass();

        generate_site_content_methods_m::update($method_name, [
            "method_name"        => $method_name,
            "method_requirments" => $method_requirments,
            "method_img"         => [
                "alt"   => "",
                "title" => "",
                "path"  => "",
            ],
        ]);

        foreach ($serFields as $field) {
            if (!isset($defaultValues[$field])) continue;
            self::addArrFieldValue($method_name, $field, $defaultValues[$field]);
        }

        self::removeCacheSiteContent($method_name, config("default_language.main_lang_title"));

    }


    //add new keyword value with lang (en)
    public static function addNormalFieldValue($content_method, $field_name)
    {

        $lang_title = config("default_language.main_lang_title");

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field_name} = capitalize_string($field_name);

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }

    public static function addArrFieldValue($content_method, $field_name, $values)
    {

        $lang_title = config("default_language.primary_lang_title");

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field_name} = $values;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }


    private static function getSiteContentByMethodTitle(string $content_method, string $lang_title): object
    {

        //get site content by title
        $site_content = site_content_m::getWhere($lang_title, $content_method);

        if (!is_object($site_content)) {
            //create new row
            $site_content = site_content_m::create($lang_title, $content_method, [
                "content_json" => []
            ]);
        }

        if (!is_object($site_content->content_json)) {
            $site_content->content_json = new \stdClass();
        }

        return $site_content->content_json;

    }

    public static function saveNormalFieldValue($content_method, $field_name, $fieldValue, $lang_title)
    {

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field_name} = $fieldValue;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }

    public static function saveImageFieldValue($content_method, $field, $property, $fieldValue, $lang_title)
    {

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        if (
            !isset($content_json->img_ids->{$field}) ||
            !isset($content_json->img_ids->{$field}->{$property})

        ) {
            return;
        }

        $content_json->img_ids->{$field}->{$property} = $fieldValue;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }

    public static function saveArrayFieldValue($content_method, $field, $index, $fieldValue, $lang_title)
    {

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field}[$index] = $fieldValue;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }

    public static function saveSliderImageFieldValue($content_method, $field, $index, $property, $fieldValue, $lang_title)
    {

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field}->img_ids[$index]->{$property} = $fieldValue;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }

    public static function saveSliderOtherFieldValue($content_method, $field, $index, $property, $fieldValue, $lang_title)
    {

        $content_json = self::getSiteContentByMethodTitle($content_method, $lang_title);

        $content_json->{$field}->other_fields->{$property}[$index] = $fieldValue;

        site_content_m::update($lang_title, $content_method, [
            "content_json" => $content_json
        ]);

    }


}
