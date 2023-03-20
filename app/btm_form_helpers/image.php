<?php

namespace App\btm_form_helpers;

use App\models\attachments_m;

class image
{

    //not used any more outside this class
    private static function edit_slider_item(\Illuminate\Http\Request $request)
    {

        $img_id = $request->get("img_id");
        $output = [];

        $output["test"] = is_numeric($img_id);

        if (!is_numeric($img_id)) {
            return self::edit_slider_item_without_attachment($request);
        }

        $att_obj = attachments_m::find($img_id);

        if (!is_object($att_obj)) {
            $att_obj = attachments_m::create([
                "id"    => $img_id,
                "path"  => "",
                "title" => "",
                "alt"   => ""
            ]);
        }

        $upload_path = self::cms_upload_v_2($request, [
            "img_file_name"    => "new_file",
            "upload_file_path" => "/edit_slider_items_without_attachment",
        ]);

        $output["msg"] = "Failed";

        if (isset_and_array($upload_path)) {
            $att_obj->update([
                "path" => $upload_path[0]
            ]);

            $output["file_path"] = url("/" . $upload_path[0]);
            $output["msg"]       = "Done";
        }

        return json_encode($output);
    }

    public static function edit_slider_item_without_attachment(\Illuminate\Http\Request $request)
    {
        $output = [];

        $output["without_attachment"] = "yes";

        $old_img_path = $request->get("img_id");

        $upload_path = self::cms_upload(
            $request,
            $file_name = "new_file",
            $folder = "/new_slider_items",
            $width = 0,
            $height = 0,
            $ext_arr = array(),
            $return_only_name = false,
            $absolute_upload_path = ""
        );

        $output["msg"] = "Failed";

        if (!isset_and_array($upload_path)) {
            return json_encode($output);
        }

        $old_img_path = explode("?", $old_img_path);

        $changed_at = "";
        if (isset($old_img_path[1])) {
            $changed_at = "?" . $old_img_path[1];
        }

        $old_img_path = $old_img_path[0];

        file_put_contents($old_img_path, file_get_contents($upload_path[0]));

        unlink($upload_path[0]);

        $output["file_path_with_time"] = $old_img_path . $changed_at;
        $output["real_file_path"]      = $old_img_path;
        $output["msg"]                 = "Done";
        return json_encode($output);
    }

    public static function cms_upload($request, $file_name, $folder, $width = 0, $height = 0, $ext_arr = array(), $return_only_name = false, $absolute_upload_path = "")
    {

        $uploaded = array();
        if (!empty($file_name) && isset($request)) {

            if ($file_objs = $request->file($file_name)) {
                if (!is_array($file_objs)) {
                    $file_objs = array($file_objs);
                }

                foreach ($file_objs as $key => $file_obj) {

                    if ($file_obj == null) {
                        continue;
                    }

                    $uploaded_file_ext            = $file_obj->getClientOriginalExtension();
                    $uploaded_origin_file_name    = $file_obj->getClientOriginalName() . '.' . $uploaded_file_ext;
                    $uploaded_file_encrypted_name = md5(rand(000, 999) . time() . $file_name . $file_obj->getClientOriginalName()) . "." . $uploaded_file_ext;
                    $uploaded_file_path           = "uploads" . $folder;

                    if ($absolute_upload_path != "") {
                        $uploaded_file_path = $absolute_upload_path;
                    }

                    $uploaded_full_path_to_file = $uploaded_file_path . '/' . $uploaded_file_encrypted_name;

                    if (in_array($uploaded_file_ext, array( "jpeg", "png", "jpg", "JPEG", "PNG", "JPG", "xls", "XLS", "doc", "docx", "xlsx", "XLSX", "csv", "CSV", "pdf", "PDF", "gif", "GIF", "svg")) || (count($ext_arr) > 0 && in_array($uploaded_file_ext, $ext_arr))) {
                        $file_obj->move($uploaded_file_path, $uploaded_file_encrypted_name);

                        if ($width > 0 && $height > 0) {
                            $img = \Intervention\Image\Facades\Image::make(($uploaded_full_path_to_file))->resize($width, $height);
                            $img->save(($uploaded_full_path_to_file), 100);
                        }

                        if ($return_only_name == true || $return_only_name == "true") {
                            $uploaded[] = $uploaded_file_encrypted_name;
                        }
                        else {
                            $uploaded[] = $uploaded_full_path_to_file;
                        }

                    }
                    else {
                        return "not allowed type";
                    }

                }


            }
            else {
                return "There is not file to upload";
            }


        }
        else {
            return "There is not input file or comming request !!";
        }

        return $uploaded;

    }

    public static function cms_upload_v_2($request, $params = [])
    {

        $actual_params = [
            "img_file_name"        => "",
            "upload_file_path"     => "",
            "width"                => 0,
            "height"               => 0,
            "ext_arr"              => [],
            "return_only_name"     => false,
            "absolute_upload_path" => ""
        ];

        foreach ($actual_params as $key => $actual_param) {
            if (isset($params[$key])) {
                $actual_params[$key] = $params[$key];
            }
        }

        $actual_params = array_merge([$request], $actual_params);


        return call_user_func_array(['\\App\btm_form_helpers\image', 'cms_upload'], $actual_params);
    }

    //not used any more outside this class
    private static function general_save_img($request, $item_id = null, $img_file_name, $new_title, $new_alt, $upload_new_img_check, $upload_file_path, $width, $height, $photo_id_for_edit, $ext_arr = array())
    {

        $new_title = ($new_title == null) ? "" : $new_title;
        $new_alt   = ($new_alt == null) ? "" : $new_alt;

        //$item_id could be pro id , cat_id any thing
        $photo_id = "not_enter";

        $upload_img = self::cms_upload($request, $img_file_name, $upload_file_path, $width, $height, $ext_arr);

        if ($item_id == null) {
            //save attachment first

            if (
                !is_array($upload_img) ||
                (is_array($upload_img) && !(count($upload_img) > 0))
            ) {
                return 0;
            }

            //save main photo
            $upload_img = $upload_img[0];

            $photo_id = attachments_m::save_img(null, $new_title, $new_alt, $upload_img);

            return $photo_id;
        }//end check of upload file


        if ($item_id != null && $photo_id_for_edit > 0) {
            //edit photo data
            //update image info

            if (is_array($upload_img) && $upload_new_img_check == "on") {
                $photo_id = attachments_m::save_img($photo_id_for_edit, $new_title, $new_alt, $upload_img[0]);
                return $photo_id;
            }
            $photo_id = attachments_m::save_img($photo_id_for_edit, $new_title, $new_alt);
        }

        if ($item_id != null && $photo_id_for_edit == 0) {
            //add new photo data if edit item has new image
            if (is_array($upload_img) && $upload_new_img_check == "on") {
                $photo_id = attachments_m::save_img($photo_id_for_edit, $new_title, $new_alt, $upload_img[0]);
                return $photo_id;
            }
            elseif (is_array($upload_img) && count($upload_img) > 0) {
                $photo_id = attachments_m::save_img($photo_id_for_edit, $new_title, $new_alt, $upload_img[0]);
                return $photo_id;
            }
            else {
                return $photo_id_for_edit;
            }

        }

        return $photo_id;
    }

    //not used any more outside this class
    private static function general_save_slider($request, $field_name = "", $width = 0, $height = 0, $new_title_arr, $new_alt_arr, $json_values_of_slider = "", $old_title_arr, $old_alt_arr, $path = "")
    {

        if ($path == "") {
            $path = $field_name;
        }
        //upload new files
        $slider_file = self::cms_upload($request, "$field_name", $folder = "$path", $width, $height);//array

        //update old_photos
        if (is_array($json_values_of_slider) && count($json_values_of_slider)) {
            foreach ($json_values_of_slider as $key => $value) {
                $save_img_title = "";
                if (isset($old_title_arr[$key])) {
                    $save_img_title = $old_title_arr[$key];
                }

                $save_img_alt = "";
                if (isset($old_alt_arr[$key])) {
                    $save_img_alt = $old_alt_arr[$key];
                }

                $old_photo_id = attachments_m::save_img($value, $save_img_title, $save_img_alt);
            }
        }

        //add new photos
        if (is_array($slider_file) && count($slider_file)) {
            foreach ($slider_file as $key => $value) {
                $save_img_title = "";
                if (isset($new_title_arr[$key])) {
                    $save_img_title = $new_title_arr[$key];
                }

                $save_img_alt = "";
                if (isset($new_alt_arr[$key])) {
                    $save_img_alt = $new_alt_arr[$key];
                }

                $json_values_of_slider[] = attachments_m::save_img(null, $save_img_title, $save_img_alt, $value);
            }//end foreach
        }

        return $json_values_of_slider;
    }

    //not used any more outside this class
    private static function general_save_img_v_2(\Illuminate\Http\Request $request, $arr = [])
    {
        $fields = [
            "img_file_name"        => "",
            "item_id"              => null,
            "new_title"            => "",
            "new_alt"              => "",
            "upload_new_img_check" => "",
            "upload_file_path"     => "",
            "width"                => "",
            "height"               => "",
            "photo_id_for_edit"    => "",
            "ext_arr"              => [],
        ];

        if (!isset($arr["img_file_name"])) return die("forget to send file name");

        foreach ($fields as $field => $value) {
            $fields[$field] = isset($arr[$field]) ? $arr[$field] : $value;
        }

        $fields = array_merge([$request], $fields);


        return call_user_func_array(['\\App\btm_form_helpers\image', 'general_save_img'], $fields);
    }

    public static function general_save_img_without_attachment(\Illuminate\Http\Request $request, $params = [])
    {

        $avaliable_params = [
            "old_path"             => "",
            "img_file_name"        => "",
            "new_title"            => "",
            "new_alt"              => "",
            "upload_new_img_check" => "",
            "upload_file_path"     => "/all_imgs",
            "width"                => "",
            "height"               => "",
            "ext_arr"              => array(),
            "absolute_upload_path" => ""
        ];

        foreach ($avaliable_params as $field => $value) {
            $avaliable_params[$field] = isset($params[$field]) ? $params[$field] : $value;
        }

        if (!is_file($avaliable_params["old_path"])) {
            $avaliable_params["old_path"] = "";
        }

        if (!isset($avaliable_params["img_file_name"])) return die("forget to send file name");

        $new_img        = new \stdClass();
        $new_img->alt   = $avaliable_params["new_alt"];
        $new_img->title = $avaliable_params["new_title"];

        $upload_img = self::cms_upload_v_2($request, $avaliable_params);

        if (is_array($upload_img)) {
            $new_img->path = $upload_img[0];
            return $new_img;
        }
        else {
            $new_img->path = $avaliable_params["old_path"];
            return $new_img;
        }

    }

    public static function general_save_slider_without_attachment(\Illuminate\Http\Request $request, $params = [])
    {

        $avaliable_params = [
            "img_file_name"         => "",
            "width"                 => 0,
            "height"                => 0,
            "new_title_arr"         => "",
            "new_alt_arr"           => "",
            "json_values_of_slider" => "",
            "old_title_arr"         => "",
            "old_alt_arr"           => "",
            "upload_file_path"      => "",
            "absolute_upload_path"  => "",
            "return_without_encode" => false
        ];

        foreach ($avaliable_params as $field => $value) {
            $avaliable_params[$field] = isset($params[$field]) ? $params[$field] : $value;
        }

        if (!isset($avaliable_params["img_file_name"])) return die("forget to send file name");

        $slider_imgs_objs = [];

        //upload new files
        $slider_file = self::cms_upload_v_2($request, $avaliable_params);

        //update old_photos
        if (is_array($avaliable_params["json_values_of_slider"]) && count($avaliable_params["json_values_of_slider"])) {

            //remove null values caused of delete imgs i tries to do array_diff but it doesn't work at array of objs
            $old_objs = [];
            foreach ($avaliable_params["json_values_of_slider"] as $key => $value) {
                if (!is_object($value)) continue;

                $old_objs[] = $value;
            }

            foreach ($old_objs as $key => $value) {

                $save_img_title = "";
                if (isset($avaliable_params["old_title_arr"][$key])) {
                    $save_img_title = $avaliable_params["old_title_arr"][$key];
                }

                $save_img_alt = "";
                if (isset($avaliable_params["old_alt_arr"][$key])) {
                    $save_img_alt = $avaliable_params["old_alt_arr"][$key];
                }

                $slider_imgs_objs[] = [
                    "id"    => (count($slider_imgs_objs) + 1),
                    "path"  => $value->path,
                    "alt"   => $save_img_alt,
                    "title" => $save_img_title
                ];
            }
        }

        //add new photos
        if (is_array($slider_file) && count($slider_file)) {
            foreach ($slider_file as $key => $value) {
                $save_img_title = "";
                if (isset($avaliable_params["new_title_arr"][$key])) {
                    $save_img_title = $avaliable_params["new_title_arr"][$key];
                }

                $save_img_alt = "";
                if (isset($avaliable_params["new_alt_arr"][$key])) {
                    $save_img_alt = $avaliable_params["new_alt_arr"][$key];
                }

                $slider_imgs_objs[] = [
                    "id"    => (count($slider_imgs_objs) + 1),
                    "path"  => $value,
                    "alt"   => $save_img_alt,
                    "title" => $save_img_title
                ];
            }//end foreach
        }

        if ($avaliable_params["return_without_encode"]) {
            return $slider_imgs_objs;
        }

        return json_encode($slider_imgs_objs);

    }

    public static function move_img_to_another_folder($img_path, $new_folder)
    {

        $img_name = $img_path;
        $img_name = explode("/", $img_name);
        $img_name = $img_name[count($img_name) - 1];

        if (!file_exists($new_folder)) {
            mkdir($new_folder, 0777, true);
        }


        $filename = "{$new_folder}/{$img_name}";
        $file     = fopen($filename, "w");

        file_put_contents(
            $filename,
            file_get_contents($img_path)
        );

        fclose($file);

        return $filename;
    }

    public static function convert_img_id_to_obj($img_id, $save_folder = "uploads")
    {
        $img_obj_db = attachments_m::find($img_id);

        $img_obj        = new \stdClass();
        $img_obj->path  = "";
        $img_obj->alt   = "";
        $img_obj->title = "";

        if (is_object($img_obj_db)) {
            $img_obj->path  = $img_obj_db->path;
            $img_obj->alt   = $img_obj_db->alt;
            $img_obj->title = $img_obj_db->title;
        }

        if (file_exists($img_obj->path)) {
            $img_obj->path = self::move_img_to_another_folder($img_obj->path, $save_folder);
        }
        else {
            $img_obj->path = "";
        }

        return $img_obj;
    }

    public static function getFormattedImage($folder, $target_image)
    {

        abort_if((!isset($target_image)), 404);

        $get_segments   = \Illuminate\Support\Facades\Request::segments();
        $count_segments = count($get_segments);
        if ($count_segments > 1) {
            $target_image = $get_segments[$count_segments - 1];
            $folder       = $get_segments[$count_segments - 2];
        }

        $generate_path = $get_segments[0] . "/";
        foreach ($get_segments as $key => $item) {

            if ($key == 0 || $key == ($count_segments - 1)) {
                continue;
            }

            $generate_path .= "$item/";
        }

        $target_image = explode('-', $target_image);

        abort_if((!isset($target_image[1])), 404);

        $return_image_url = get_format_image("$generate_path" . $target_image[0], $target_image[1]);

        return \Image::make($return_image_url)->response();
    }
}
