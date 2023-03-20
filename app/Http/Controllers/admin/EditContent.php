<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\image;
use App\btm_form_helpers\site_content;
use App\Http\Controllers\AdminBaseController;
use App\Jobs\Translate\TranslateSiteContentMethodFromEnToLang;
use App\models\generate_site_content_methods_m;
use App\models\langs_m;
use Illuminate\Http\Request;
use App\models\site_content_m;
use Illuminate\Support\Facades\Redirect;

class EditContent extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->getAllLangs();

        havePermissionOrRedirect("admin/site_content","can_edit_content");

    }

    public function show_methods(Request $request)
    {

        $this->getAllLangs();

        $this->data["methods"] = generate_site_content_methods_m::all();

        return $this->returnView($request, "admin.subviews.edit_content.show_methods");

    }

    public function showContentSpansAtFront(Request $request){

        $show_admin_content = $request->get('show_admin_content');

        $request->session()->put('show_admin_content',$show_admin_content);
        $request->session()->save();

        if ($show_admin_content == "no") {
            return [
                "msg" => "everything returns to its normal, badges are hidden"
            ];
        }

        return [
            "msg" => "
                you see badges now with edit icon at each keyword at site you can edit any keyword by click at it <br>
                you also can edit site-content-images by clicking at it <br>
                go to homepage and see the badges
            "
        ];

    }


    public function check_function(Request $request, $lang_title, $slug)
    {

        $get_this_method = generate_site_content_methods_m::findOrFail($slug);

        $get_this_method_setting = $get_this_method->method_requirments;
        $get_this_method_setting = json_decode(json_encode($get_this_method_setting), true);

        //input_fields
        $input_fields = array();
        if (isset($get_this_method_setting["input_fields"]["fields"])) {
            $input_fields          = generate_fields_for_edit_content($get_this_method_setting["input_fields"]["fields"], "textarea");
            $input_fields_cutomize = $get_this_method_setting["input_fields"]["customize"];

            if (isset($input_fields_cutomize)) {
                foreach ($input_fields_cutomize as $cust_type_key => $cust_type_value) {
                    foreach ($cust_type_value as $cust_field_key => $cust_field_value) {
                        $input_fields[$cust_field_key][$cust_type_key] = $cust_field_value;
                    }
                }
            }

            $input_fields = reformate_arr_without_keys($input_fields);
        }

        //END input_fields

        //arr_fields
        $arr_fields = array();
        if (isset($get_this_method_setting["arr_fields"]["fields"])) {
            $fields_arr           = $get_this_method_setting["arr_fields"]["fields"];
            $fields_arr_customize = $get_this_method_setting["arr_fields"]["customize"];


            foreach ($fields_arr as $key => $set) {
                $set = generate_arr_fields_for_edit_content($set);
                //check if there is customization for this set or not
                if (isset($fields_arr_customize[$key])) {
                    foreach ($fields_arr_customize[$key] as $cust_type_key => $cust_type_value) {
                        foreach ($cust_type_value as $cust_field_key => $cust_field_value) {
                            $set[$cust_field_key][$cust_type_key] = $cust_field_value;
                        }
                    }
                }

                $set              = reformate_arr_without_keys($set);
                $arr_fields[$key] = $set;
            }
        }
        //END arr_fields

        //imgs_fields
        $imgs_fields = array();
        if (isset($get_this_method_setting["imgs_fields"]["fields"])) {
            $fields               = $get_this_method_setting["imgs_fields"]["fields"];
            $imgs_fields_cutomize = $get_this_method_setting["imgs_fields"]["customize"];
            $imgs_fields          = generate_imgs_fields_for_edit_content($fields);

            if (isset($imgs_fields_cutomize)) {
                foreach ($imgs_fields_cutomize as $cust_type_key => $cust_type_value) {
                    foreach ($cust_type_value as $cust_field_key => $cust_field_value) {
                        $imgs_fields[$cust_field_key][$cust_type_key] = $cust_field_value;
                    }
                }
            }

            $imgs_fields = reformate_arr_without_keys($imgs_fields);
        }
        //END imgs_fields

        //slider_fields
        $slider_fields = array();
        if (isset($get_this_method_setting["slider_fields"]["fields"])) {
            $slider_fields_items = $get_this_method_setting["slider_fields"]["fields"];


            $items_arr = array();
            foreach ($slider_fields_items as $key => $item) {
                $items_arr[] = ["field_name" => $item];
            }

            $slider_fields = generate_slider_fieldes_for_edit_content($items_arr);

            foreach ($slider_fields as $slider_item_key => $slider_item) {


                if (isset($get_this_method_setting["slider_fields"]["customize"][$slider_item_key])) {
                    $slider_fields_cutomize = $get_this_method_setting["slider_fields"]["customize"]["$slider_item_key"];
                    foreach ($slider_fields_cutomize as $cust_type_key => $cust_type_value) {
                        $slider_fields["$slider_item_key"]["$cust_type_key"] = "$cust_type_value";
                    }
                }
            }

            $slider_fields = reformate_arr_without_keys($slider_fields);
        }
        //END slider_fields

        //select fields

        $select_fields = array();
        if (isset($get_this_method_setting["select_fields"]["fields"])) {
            $select_fields_items     = $get_this_method_setting["select_fields"]["fields"];
            $select_fields_models    = $get_this_method_setting["select_fields"]["tables"];
            $select_fields_customize = $get_this_method_setting["select_fields"]["customize"];

            $select_fields = generate_select_tags_for_edit_content($select_fields_items);

            //get select tables data
            foreach ($select_fields as $key => $field) {
                $table_name = $select_fields_models[$key]["model"];
                $text_col   = $select_fields_models[$key]["text_col"];
                $val_col    = $select_fields_models[$key]["val_col"];


                if (!empty($table_name) && !empty($text_col) && !empty($val_col)) {
                    $table_data_values = $table_name::All()->lists("$text_col", "$val_col")->keys()->all();
                    $table_data_text   = $table_name::All()->lists("$text_col", "$val_col")->values()->all();

                    $select_fields[$key]["values"] = $table_data_values;
                    $select_fields[$key]["text"]   = $table_data_text;
                }
                else {
                    unset($select_fields[$key]);
                }
            }


            //customize

            if (isset($select_fields_customize)) {
                foreach ($select_fields_customize as $cust_type_key => $cust_type_value) {
                    foreach ($cust_type_value as $cust_field_key => $cust_field_value) {
                        if (isset($select_fields[$cust_field_key])) {
                            $select_fields[$cust_field_key][$cust_type_key] = $cust_field_value;
                        }
                    }
                }
            }

            $select_fields = reformate_arr_without_keys($select_fields);
        }

        //END select fields


        return $this->general_edit_content(
            $method_request = $request,
            $input_fields,
            $arr_fields,
            $imgs_fields,
            $content_id = $get_this_method->method_name,
            $content_title = $get_this_method->method_name,
            $content_method = $get_this_method->method_name,
            $img_path = is_object($get_this_method->method_img) ? $get_this_method->method_img->path : "",
            $slider_fields,
            $select_fields,
            $lang_title
        );

    }

    private function general_edit_content(
        Request $method_request,
        $input_fields = array(),
        $arr_fields = array(),
        $imgs_fields = array(),
        $content_id,
        $content_title = "General Edit Content",
        $content_method = "",
        $img_path = "",
        $slider_fields = array(),
        $select_fields = array(),
        $lang_title = ''
    )
    {

        if(empty($lang_title)){
            $lang_title = config("default_language.main_lang_title");
        }

        $this->data["content_id"]        = $content_id;
        $this->data["content_title"]     = $content_title;
        $this->data["content_method"]    = $content_method;
        $this->data["img_path"]          = $img_path;
        $this->data["method_lang_title"] = $lang_title;
        $this->data["current_lang"]      = langs_m::where("lang_title", $lang_title)->get()->first();

        $this->data["content_data"] = new \stdClass();

        //get site content by title
        $site_content = site_content_m::getWhere($lang_title, $content_method);

        if (!is_object($site_content)) {
            //create new row
            $site_content = site_content_m::create($lang_title, $content_method, [
                "content_json" => []
            ]);
        }

        $content_json = $site_content->content_json;
        $this->data["content_data"] = $content_json;

        if(!is_object($this->data["content_data"])){
            $this->data["content_data"] = new \stdClass();
        }

        //generate form tags
        $this->data["normal_tags"] = $input_fields;

        //select tags
        $this->data["select_tags"] = $select_fields;


        $new_imgs_fields = array();
        foreach ($imgs_fields as $key => $img) {
            $old_img = array();

            if (isset($this->data["content_data"]->img_ids->{$img["field_name_without_brackets"]})) {
                $old_img = $this->data["content_data"]->img_ids->{$img["field_name_without_brackets"]};
            }

            if (!is_object($old_img)) {
                $old_img        = new \stdClass();
                $old_img->path  = "";
                $old_img->alt   = "";
                $old_img->title = "";
            }

            $img["img"]        = $old_img;
            $new_imgs_fields[] = $img;

        }

        $this->data["imgs_tags"] = $new_imgs_fields;
        $this->data["arr_tags"]  = $arr_fields;

        foreach ($slider_fields as $key => $slider) {
            $slider = "slider" . ($key + 1);

            if (!isset($this->data["content_data"]->{$slider})) {
                $this->data["content_data"]->{$slider} = new \stdClass();
            }


            $this->data["content_data"]->{$slider}->slider_objs = [];


            if (
                isset($this->data["content_data"]->{$slider}->img_ids) &&
                is_array($this->data["content_data"]->{$slider}->img_ids) &&
                count($this->data["content_data"]->{$slider}->img_ids)
            ) {
                $this->data["content_data"]->{$slider}->slider_objs = $this->data["content_data"]->{$slider}->img_ids;
            }
        }

        $this->data["slider_fields"] = $slider_fields;

        //END generate form tags

        if ($method_request->method() == "POST") {

            site_content::removeCacheSiteContent($content_method,$lang_title);

            //save
            $inputs = array();

            //save form

            //add normal fields
            if (is_array($input_fields) && count($input_fields)) {
                $input_fields = collect($input_fields)->pluck("field_name")->all();
                $inputs       = $method_request->only($input_fields);
            }
            //END add normal Fields

            //select fields
            $input_fields  = collect($select_fields)->pluck("field_name")->all();
            $select_inputs = $method_request->only($input_fields);
            $inputs        = array_merge($inputs, $select_inputs);
            //END select fields


            //add img fields
            $img_ids = array();
            if (is_array($imgs_fields) && count($imgs_fields)) {
                foreach ($imgs_fields as $key => $img_field) {

                    $old_img = "";

                    if (isset($this->data["content_data"]->img_ids->{$img_field["field_name_without_brackets"]})) {
                        $old_img = $this->data["content_data"]->img_ids->{$img_field["field_name_without_brackets"]};
                    }

                    if (!is_object($old_img)) {
                        $old_img        = new \stdClass();
                        $old_img->path  = "";
                        $old_img->alt   = "";
                        $old_img->title = "";
                    }

                    $new_title_field = $img_field["field_name_without_brackets"] . "title";
                    $new_alt_field   = $img_field["field_name_without_brackets"] . "alt";


                    $upload_new_img_checkbox = $img_field["field_name_without_brackets"] . "_upload_new_img_chcekbox";

                    $imgs_inputs = $method_request->only(array(
                        $upload_new_img_checkbox, $new_title_field, $new_alt_field
                    ));


                    if ($img_field["need_alt_title"] == "no") {
                        $imgs_inputs[$new_title_field] = "";
                        $imgs_inputs[$new_alt_field]   = "";

                    }

                    $img_ids[$img_field["field_name_without_brackets"]] =
                        image::general_save_img_without_attachment($method_request, [
                            "old_path"             => $old_img->path,
                            "img_file_name"        => $img_field["field_name_without_brackets"],
                            "new_title"            => $imgs_inputs[$new_title_field],
                            "new_alt"              => $imgs_inputs[$new_alt_field],
                            "upload_new_img_check" => $method_request->get($upload_new_img_checkbox),
                            "width"                => $img_field["width"],
                            "height"               => $img_field["height"],
                            "absolute_upload_path" => "site_content/data/lang_{$lang_title}/{$content_title}"
                        ]);


                }
            }//end imgs if

            $inputs["img_ids"] = $img_ids;
            //END img Fields

            //add array_input fields
            if (is_array($arr_fields) && count($arr_fields)) {
                foreach ($arr_fields as $key => $single_arr_fields) {
                    $single_arr_fields                 = collect($single_arr_fields)->pluck("field_name")->all();
                    $inputs_new                        = $method_request->only($single_arr_fields);
                    $inputs_new[$single_arr_fields[0]] = array_diff($inputs_new[$single_arr_fields[0]], array(""));

                    $inputs = array_merge($inputs, $inputs_new);
                }
            }
            //END add array_input fields

            //add sliders
            if (is_array($slider_fields) && count($slider_fields)) {
                foreach ($slider_fields as $key => $slider_field) {

                    $field_name = $slider_field["field_name"];

                    $new_title_field_name = $field_name . "_title";
                    $new_alt_field_name   = $field_name . "_alt";

                    $old_title_field_name = $field_name . "_edit_title";
                    $old_alt_field_name   = $field_name . "_edit_alt";

                    $json_values_of_slider_field = "json_values_of_slider" . $field_name;

                    $other_fields = $slider_field["additional_inputs_arr"];
                    if (!empty($other_fields) && !is_array($other_fields)) {
                        $other_fields = explode(",", $other_fields);
                    }

                    $slider_inputs = $method_request->only(array(
                        $new_title_field_name, $new_alt_field_name,
                        $json_values_of_slider_field,
                        $old_title_field_name, $old_alt_field_name
                    ));


                    $slider_inputs[$json_values_of_slider_field] = json_decode($method_request->get($json_values_of_slider_field));

                    $inputs["slider" . ($key + 1)]["img_ids"] = image::general_save_slider_without_attachment(
                        $method_request,
                        [
                            "img_file_name"         => $field_name,
                            "width"                 => $slider_field["width"],
                            "height"                => $slider_field["height"],
                            "new_title_arr"         => $method_request->get($new_title_field_name),
                            "new_alt_arr"           => $method_request->get($new_alt_field_name),
                            "json_values_of_slider" => $slider_inputs[$json_values_of_slider_field],
                            "old_title_arr"         => $method_request->get($old_title_field_name),
                            "old_alt_arr"           => $method_request->get($old_alt_field_name),
                            "upload_file_path"      => "",
                            "absolute_upload_path"  => "site_content/data/lang_{$lang_title}/{$content_title}",
                            "return_without_encode" => true
                        ]
                    );


                    if (is_array($other_fields)) {
                        $new_fields = $method_request->only($other_fields);

                        $old_other_fields = array();
                        foreach ($other_fields as $field_key => $field_value) {
                            $old_other_fields[] = "edit_" . $field_value;
                        }
                        $old_fields = $method_request->only($old_other_fields);


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

                        $inputs["slider" . ($key + 1)]["other_fields"] = $all_data;
                    }


                }//end foreach
            }
            //END add sliders


            site_content_m::update($lang_title, $content_method,[
                "content_json" => $inputs,
            ]);


            return [
                "msg"      => "Data Successfully Updated",
                "redirect" => url("/admin/site-content/edit_content/{$lang_title}/{$content_id}")
            ];

            return \redirect()->with("msg", "
                <div class='alert alert-info'>Done</div>
            ")->send();

        }//end submit

        return $this->returnView($method_request, "admin.subviews.edit_content.general_edit_content");
    }

    public function copy_from_lang_to_another(Request $request)
    {

//        havePermissionOrRedirect("admin/site_content","copy_from_lang_to_another");

        $this->data["all_methods"] = generate_site_content_methods_m::all();

        if ($request->method()=="POST"){

            $methodNames = [$request->get("method_name")];
            if($request->get("method_name") == "all"){
                $methodNames = $this->data["all_methods"]->pluck("method_name")->all();
            }

            if($request->get("to_lang") == "all"){

                $langs = langs_m::where("lang_title","!=","en")->get();

                foreach ($methodNames as $method){
                    foreach ($langs as $lang){
                        $this->copy_single_method(
                            $request,
                            $request->get("from_lang"),
                            $lang->lang_title,
                            $method
                        );
                    }
                }

                return $this->returnMsgWithRedirection(
                    $request,
                    "admin/site-content/copy_from_lang_to_another",
                    "Done"
                );

            }
            else{
                foreach ($methodNames as $method){
                    $this->copy_single_method(
                        $request,
                        $request->get("from_lang"),
                        $request->get("to_lang"),
                        $method
                    );
                }
            }

        }

        return $this->returnView($request, "admin.subviews.edit_content.copy_data");

    }

    private function copy_single_method(Request $request, string $from_lang, string $to_lang, string $method_name)
    {

        if ($from_lang == $to_lang) {

            return $this->returnMsgWithRedirection(
                $request,
                "admin/site-content/show_methods",
                "from and to are matched. doesn't make any sense"
            );

        }


        $from_dir = "site_content/data/lang_{$from_lang}";
        $to_dir   = "site_content/data/lang_{$to_lang}";

        if (!file_exists($to_dir)) {
            mkdir($to_dir,0775);
            chmod($to_dir, 0775);
        }


        if (!file_exists($from_dir)) {

            return $this->returnMsgWithRedirection(
                $request,
                "admin/site-content/show_methods",
                "The data of selected from lang isn't entered yet"
            );

        }

        $from_sub_dirs = [$method_name];

        if (!file_exists($from_dir . "/" . "{$method_name}.json")) {

            return $this->returnMsgWithRedirection(
                $request,
                "admin/site-content/show_methods",
                "The data of selected item from lang isn't entered yet"
            );

        }

        $file_data  = file_get_contents($from_dir . "/" . "{$method_name}.json");
        $file_data  = str_replace("lang_{$from_lang}", "lang_{$to_lang}", $file_data);
        $fopen_file = fopen($to_dir . "/" . "{$method_name}.json", "w");
        chmod($to_dir . "/" . "{$method_name}.json", 0775);

        file_put_contents($to_dir . "/" . "{$method_name}.json", $file_data);
        fclose($fopen_file);


        foreach ($from_sub_dirs as $sub_dir) {

            if(!file_exists($from_dir . "/" . $sub_dir)){
                continue;
            }

            $files = scandir($from_dir . "/" . $sub_dir, 1);
            $files = array_diff($files, [".", ".."]);

            if (!file_exists($to_dir . "/" . $sub_dir)) {
                mkdir($to_dir . "/" . $sub_dir, 0777, true);
                chmod($to_dir . "/" . $sub_dir, 0775);
            }


            foreach ($files as $file) {
                $fopen_file = fopen($to_dir . "/" . $sub_dir . "/" . $file, "w");
                chmod($to_dir . "/" . $sub_dir . "/" . $file, 0775);

                file_put_contents(
                    $to_dir . "/" . $sub_dir . "/" . $file,
                    file_get_contents($from_dir . "/" . $sub_dir . "/" . $file)
                );
                fclose($fopen_file);
            }

        }

        return $this->returnMsgWithRedirection(
            $request,
            "admin/site-content/copy_from_lang_to_another",
            "Done"
        );


    }

}
