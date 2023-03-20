<?php

namespace App\Http\Controllers\dev;

use App\btm_form_helpers\image;
use App\Http\Controllers\DevController;
use App\models\site_content_m;
use Illuminate\Http\Request;
use App\models\generate_site_content_methods_m;

class GenerateSiteContent extends DevController
{

    public function __construct()
    {
        parent::__construct();

    }

    public function show_all_methods(Request $request)
    {

        $this->data["all_methods"] = generate_site_content_methods_m::all();

        return $this->returnView($request, "dev.subviews.generate_edit_content.show_all");

    }

    public function save_method(Request $req, $method_name = null)
    {

        $this->data["method_data"] = "";

        if ($method_name != null) {
            $this->data["method_data"] = generate_site_content_methods_m::findOrFail($method_name);

            if (!isset($this->data["method_data"]->method_img)) {
                $this->data["method_data"]->method_img        = new \stdClass();
                $this->data["method_data"]->method_img->path  = "";
                $this->data["method_data"]->method_img->alt   = "";
                $this->data["method_data"]->method_img->title = "";
            }

            $method_requirments = $this->data["method_data"]->method_requirments;

            //get method_requirments
            #region get normal_inputs
            if (isset($method_requirments->input_fields)) {
                $input_fields = $method_requirments->input_fields;
                if (isset($input_fields->fields)) {
                    $this->data["method_data"]->field_name = implode(",", $input_fields->fields);
                }
                $normal_fields_customize = array("label_name", "required", "type", "class");
                foreach ($normal_fields_customize as $key => $cust_field) {
                    if (isset($input_fields->customize->$cust_field)) {
                        $this->data["method_data"]->$cust_field = implode(",", (array)$input_fields->customize->$cust_field);
                    }
                }
            }
            #endregion get normal_inputs

            #regionget imgs_fields
            if (isset($method_requirments->imgs_fields)) {
                $imgs_fields = $method_requirments->imgs_fields;
                if (isset($imgs_fields->fields)) {
                    $this->data["method_data"]->img_field_name = implode(",", $imgs_fields->fields);
                }
                $fields_customize = array(
                    "required"       => "img_required",
                    "need_alt_title" => "img_need_alt_title",
                    "width"          => "img_width",
                    "height"         => "img_height"
                );
                foreach ($fields_customize as $cust_key => $cust_field) {
                    if (isset($imgs_fields->customize->$cust_key)) {
                        $this->data["method_data"]->$cust_field = implode(",", (array)$imgs_fields->customize->$cust_key);
                    }
                }
            }
            #endregionEND get imgs_fields

            #region get select_fields
            if (isset($method_requirments->select_fields)) {
                $select_fields = $method_requirments->select_fields;

                if (isset($select_fields->fields)) {
                    $this->data["method_data"]->select_field_name = implode(",", $select_fields->fields);
                }

                $models    = collect($select_fields->tables)->pluck("model")->all();
                $text_cols = collect($select_fields->tables)->pluck("text_col")->all();
                $val_cols  = collect($select_fields->tables)->pluck("val_col")->all();

                $this->data["method_data"]->select_model    = implode(",", $models);
                $this->data["method_data"]->select_text_col = implode(",", $text_cols);
                $this->data["method_data"]->select_val_col  = implode(",", $val_cols);

                $fields_customize = array(
                    "label_name" => "select_label_name",
                    "class"      => "select_class",
                    "multiple"   => "select_multiple"
                );
                foreach ($fields_customize as $cust_key => $cust_field) {
                    if (isset($select_fields->customize->$cust_key)) {
                        $this->data["method_data"]->$cust_field = implode(",", (array)$select_fields->customize->$cust_key);
                    }
                }
            }
            #endregion get select_fields

            #region get arr_inputs
            if (isset($method_requirments->arr_fields)) {
                $main_sets     = $method_requirments->arr_fields->fields;
                $arr_customize = $method_requirments->arr_fields->customize;

                $arr_set_name = array_keys((array)$main_sets);
                $set_fields   = array();
                $set_labels   = array();
                $set_types    = array();
                $set_classes  = array();
                $add_tiny_mce = array();

                $cust_keys = array(
                    "label_name"   => "set_labels",
                    "field_type"   => "set_types",
                    "field_class"  => "set_classes",
                    "add_tiny_mce" => "add_tiny_mce"
                );
                foreach ($main_sets as $set_key => $set) {
                    $set_fields[] = implode(",", $set);
                    foreach ($cust_keys as $cust_key => $cust_value) {
                        if (isset($arr_customize->$set_key->$cust_key)) {
                            array_push($$cust_value, implode(",", (array)$arr_customize->$set_key->$cust_key));
                        }
                        else {
                            array_push($$cust_value, "");
                        }
                    }
                }

                $this->data["method_data"]->set_name     = $arr_set_name;
                $this->data["method_data"]->set_fields   = $set_fields;
                $this->data["method_data"]->set_labels   = $set_labels;
                $this->data["method_data"]->set_types    = $set_types;
                $this->data["method_data"]->set_classes  = $set_classes;
                $this->data["method_data"]->add_tiny_mce = $add_tiny_mce;


            }
            #endregion get arr_inputs

            #region get slider_inputs
            if (isset($method_requirments->slider_fields)) {
                $slider_fields    = $method_requirments->slider_fields->fields;
                $slider_customize = $method_requirments->slider_fields->customize;

                $slider_field_name            = $slider_fields;
                $slider_label_name            = array();
                $slider_accept                = array();
                $slider_need_alt_title        = array();
                $slider_additional_inputs_arr = array();
                $slider_width                 = array();
                $slider_height                = array();


                $cust_keys = array(
                    "label_name"            => "slider_label_name",
                    "accept"                => "slider_accept",
                    "need_alt_title"        => "slider_need_alt_title",
                    "additional_inputs_arr" => "slider_additional_inputs_arr",
                    "width"                 => "slider_width",
                    "height"                => "slider_height"
                );
                foreach ($slider_field_name as $slider_key => $slider) {
                    foreach ($cust_keys as $cust_key => $cust_value) {
                        if (isset($slider_customize->$slider->$cust_key)) {
                            array_push($$cust_value, implode(",", (array)$slider_customize->$slider->$cust_key));
                        }
                        else {
                            array_push($$cust_value, "");
                        }
                    }
                }


                $this->data["method_data"]->slider_field_name            = $slider_field_name;
                $this->data["method_data"]->slider_label_name            = $slider_label_name;
                $this->data["method_data"]->slider_accept                = $slider_accept;
                $this->data["method_data"]->slider_need_alt_title        = $slider_need_alt_title;
                $this->data["method_data"]->slider_additional_inputs_arr = $slider_additional_inputs_arr;
                $this->data["method_data"]->slider_width                 = $slider_width;
                $this->data["method_data"]->slider_height                = $slider_height;


            }
            #endregion get slider_inputs
            //END get method_requirments
        }

        if ($req->method()=="POST") {
            $inputs_arr = $req->all();

            $this->validate($req, [
                "method_name" => "required",
            ]);

            $req["method_name"] = $req->get("method_name");

            $img_obj = image::general_save_img_without_attachment($req, [
                "img_file_name"        => "method_img",
                "absolute_upload_path" => 'site_content/methods/imgs',
                "old_path"             => is_object($this->data["method_data"]) ? $this->data["method_data"]->method_img->path : "",
                "upload_new_img_check" => $req->get("method_img_checkbox")
            ]);

            $inputs_arr["method_img"]  = $img_obj;
            $inputs_arr["method_name"] = string_safe($inputs_arr["method_name"]);

            $method_requirments = array();
            //format method_requirments

            #region normal fields
            //fields
            if (!empty($inputs_arr["field_name"])) {
                $method_requirments["input_fields"] = new \stdClass();
                $normal_fields                      = explode(",", $inputs_arr["field_name"]);

                $method_requirments["input_fields"]->fields = array_map("trim", $normal_fields);

                //customzie
                $method_requirments["input_fields"]->customize = new \stdClass();
                $normal_fields_customize                       = array("label_name", "required", "type", "class");
                foreach ($normal_fields_customize as $key => $normal_fields_customize_item) {
                    $customize_values = explode(",", $inputs_arr["$normal_fields_customize_item"]);
                    if (count($customize_values) == count($normal_fields)) {
                        $method_requirments["input_fields"]->customize->$normal_fields_customize_item =
                            array_combine($normal_fields, $customize_values);

                        $method_requirments["input_fields"]->customize->$normal_fields_customize_item =
                            array_map("trim", $method_requirments["input_fields"]->customize->$normal_fields_customize_item);

                    }
                }
            }
            #endregion normal fields

            #region imgs_fields
            //fields
            if (!empty($inputs_arr["img_field_name"])) {
                $method_requirments["imgs_fields"]         = new \stdClass();
                $imgs_fields                               = explode(",", $inputs_arr["img_field_name"]);
                $method_requirments["imgs_fields"]->fields = array_map("trim", $imgs_fields);

                //customzie
                $method_requirments["imgs_fields"]->customize = new \stdClass();
                $img_fields_customize                         = array(
                    "required"       => "img_required",
                    "need_alt_title" => "img_need_alt_title",
                    "width"          => "img_width",
                    "height"         => "img_height"
                );
                foreach ($img_fields_customize as $cust_key => $customize_item) {
                    $customize_values = explode(",", $inputs_arr["$customize_item"]);
                    if (count($customize_values) == count($imgs_fields)) {
                        $method_requirments["imgs_fields"]->customize->$cust_key =
                            array_combine($imgs_fields, $customize_values);

                        $method_requirments["imgs_fields"]->customize->$cust_key =
                            array_map("trim", $method_requirments["imgs_fields"]->customize->$cust_key);

                    }
                }
            }
            #endregion imgs_fields

            #region select_fields
            //fields
            if (!empty($inputs_arr["select_field_name"])) {
                $select_fields    = explode(",", $inputs_arr["select_field_name"]);
                $select_tables    = explode(",", $inputs_arr["select_model"]);
                $select_text_cols = explode(",", $inputs_arr["select_text_col"]);
                $select_val_cols  = explode(",", $inputs_arr["select_val_col"]);

                $method_requirments["select_fields"] = new \stdClass();

                $method_requirments["select_fields"]->fields = $select_fields;

                //tables
                $method_requirments["select_fields"]->tables = new \stdClass();


                foreach ($select_fields as $key => $select_field) {
                    $method_requirments["select_fields"]->tables->$select_field = new \stdClass();

                    $method_requirments["select_fields"]->tables->$select_field->model    = $select_tables[$key];
                    $method_requirments["select_fields"]->tables->$select_field->text_col = $select_text_cols[$key];
                    $method_requirments["select_fields"]->tables->$select_field->val_col  = $select_val_cols[$key];
                }

                //customzie
                $method_requirments["select_fields"]->customize = new \stdClass();
                $select_fields_customize                        = array(
                    "label_name" => "select_label_name",
                    "class"      => "select_class",
                    "multiple"   => "select_multiple"
                );

                foreach ($select_fields_customize as $cust_key => $customize_item) {
                    $customize_values = explode(",", $inputs_arr["$customize_item"]);
                    if (count($customize_values) == count($select_fields)) {
                        $method_requirments["select_fields"]->customize->$cust_key =
                            array_combine($select_fields, $customize_values);
                    }
                }
            }
            #endregion select_fields

            #region arr fields
            //fields
            $arr_main_set_fields = array_diff($inputs_arr["set_name"], array(""));

            if (count($arr_main_set_fields) > 0) {
                $method_requirments["arr_fields"]         = new \stdClass();
                $method_requirments["arr_fields"]->fields = new \stdClass();


                $arr_set_fields = $inputs_arr["set_fields"];
                foreach ($arr_main_set_fields as $key => $main_set) {
                    $method_requirments["arr_fields"]->fields->$main_set = explode(",", $arr_set_fields[$key]);
                }

                //customzie
                $method_requirments["arr_fields"]->customize = new \stdClass();
                $arr_fields_customize                        = array(
                    "label_name"   => "set_labels",
                    "field_type"   => "set_types",
                    "field_class"  => "set_classes",
                    "add_tiny_mce" => "add_tiny_mce"
                );

                foreach ($arr_main_set_fields as $set_key => $main_set) {
                    $method_requirments["arr_fields"]->customize->$main_set = new \stdClass();
                    $set_fields                                             = $method_requirments["arr_fields"]->fields->$main_set;
                    foreach ($arr_fields_customize as $cust_key => $normal_fields_customize_item) {
                        $customize_values = explode(",", $inputs_arr["$normal_fields_customize_item"][$set_key]);
                        if (count($customize_values) == count($set_fields)) {
                            $method_requirments["arr_fields"]->customize->
                            $main_set->$cust_key =
                                array_combine($set_fields, $customize_values);
                        }
                    }
                }
            }
            #endregion arr fields

            #region slider fields
            //fields
            $slider_fields = array_diff($inputs_arr["slider_field_name"], array(""));
            if (count($slider_fields) > 0) {
                $method_requirments["slider_fields"]         = new \stdClass();
                $method_requirments["slider_fields"]->fields = $slider_fields;

                //customzie
                $method_requirments["slider_fields"]->customize = new \stdClass();
                $slider_fields_customize                        = array(
                    "label_name"            => "slider_label_name",
                    "accept"                => "slider_accept",
                    "need_alt_title"        => "slider_need_alt_title",
                    "additional_inputs_arr" => "slider_additional_inputs_arr",
                    "width"                 => "slider_width",
                    "height"                => "slider_height"
                );

                foreach ($slider_fields as $key => $slider_item) {
                    $method_requirments["slider_fields"]->customize->$slider_item = new \stdClass();

                    foreach ($slider_fields_customize as $cust_key => $customize_item) {
//                            $customize_values=explode(",",$inputs_arr["$customize_item"][$key]);
                        $customize_values = $inputs_arr["$customize_item"][$key];
                        //if(count($customize_values)==count($slider_fields)){
                        $method_requirments["slider_fields"]->customize->$slider_item->$cust_key =
                            $customize_values;
                        //}
                    }

                }
            }

            #endregion slider fields

            //END format method_requirments
            $inputs_arr["method_requirments"] = $method_requirments;

            if ($method_name == null) {
                $returned_id = generate_site_content_methods_m::create($inputs_arr);
                $method_name   = $returned_id->method_name;
            }
            else {
                generate_site_content_methods_m::update($method_name, $inputs_arr);
            }



            return $this->returnMsgWithRedirection(
                $req,
                "/dev/generate_edit_content/save/$method_name",
                "Done"
            );

        }

        return $this->returnView($req, "dev.subviews.generate_edit_content.save");

    }

    public function convert_db_to_files(Request $request)
    {

        if (!\Schema::hasTable("generate_site_content_methods")) {

            return $this->returnMsgWithRedirection(
                $request,
                "dev/dashboard",
                "DB has no generate_site_content_methods table"
            );

        }

        $method_rows = \DB::select("
            select
              generate_site_content_methods.*,
              attachments.path
            from generate_site_content_methods
            left outer join attachments on attachments.id=generate_site_content_methods.method_img_id
        ");


        foreach ($method_rows as $row) {

            $row = (array)$row;

            //save imgae at new location
            if (!empty($row["path"]) && file_exists("{$row["path"]}")) {
                $row["path"] = image::move_img_to_another_folder($row["path"], "site_content/methods/imgs");
            }
            else {
                $row["path"] = "";
            }

            $row["method_img"] = [
                "path"  => $row["path"],
                "alt"   => "",
                "title" => "",
            ];

            $row["method_requirments"] = $row["method_requirments"];

            generate_site_content_methods_m::create($row);

            \DB::table('generate_site_content_methods')->where('id', $row["id"])->delete();
        }


        $data_rows = \DB::table("site_content")->get();

        foreach ($data_rows as $row) {
            $row = (array)$row;

            if (isset($row["content_json"]["img_ids"])) {
                foreach ($row["content_json"]["img_ids"] as $key => $img_id) {
                    $row["content_json"]["img_ids"][$key] = image::convert_img_id_to_obj(
                        $img_id,
                        "site_content/data/lang_{$row["lang_id"]}/{$row["content_title"]}"
                    );
                }
            }


            for ($i = 1; $i <= 20; $i++) {
                $slider_index_name = "slider" . $i;
                if (!isset($row["content_json"][$slider_index_name])) {
                    break;
                }

                foreach ($row["content_json"][$slider_index_name]["img_ids"] as $key => $img_id) {
                    $row["content_json"][$slider_index_name]["img_ids"][$key] = image::convert_img_id_to_obj(
                        $img_id,
                        "site_content/data/lang_{$row["lang_id"]}/{$row["content_title"]}"
                    );
                }
            }

            site_content_m::create($row["lang_id"], $row["content_title"], $row["content_json"]);

            \DB::table('site_content')->where('id', $row["id"])->delete();
        }

        return $this->returnMsgWithRedirection(
            $request,
            "dev/dashboard",
            "Done"
        );

    }

}
