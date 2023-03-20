<?php

function return_default_img($no_img_name = "", $default_is_on_full_path = false)
{

    if ($default_is_on_full_path)
        return $no_img_name;

    return url('/public/images/' . (empty($no_img_name) ? 'no_image.png' : $no_img_name));
}

function get_image_from_obj($obj)
{

    $path = "";
    if (isset($obj->path)) {
        $path = $obj->path;
    }

    return get_image_or_default($path);
}

function getPathfromJsonObjWithoutDefault($obj)
{


    if (is_array($obj) && count($obj) == 0) {
        return "";
    }
    if (empty($obj)) {
        return "";
    }

    if (!is_object($obj)) {
        $obj = json_decode($obj);
    }

    if (!is_object($obj)) {
        $obj = new stdClass();
    }

    $path = "";
    if (isset($obj->path)) {
        $path = $obj->path;
    }


    return url($path);
}

function get_image_from_json_obj($obj, $no_img_name = "", $default_is_on_full_path = false)
{


    if (is_array($obj) && count($obj) == 0) {
        return return_default_img($no_img_name, $default_is_on_full_path);
    }
    if (empty($obj)) {
        return return_default_img($no_img_name, $default_is_on_full_path);
    }

    if (!is_object($obj)) {
        $obj = json_decode($obj);
    }

    if (!is_object($obj)) {
        $obj = new stdClass();
    }

    $path = "";
    if (isset($obj->path)) {
        $path = $obj->path;
    }


    return get_image_or_default($path, $no_img_name, $default_is_on_full_path);
}

function get_image_alt_title($obj, $json = false)
{
    if (!$json && !is_object($obj)) {
        $obj = json_decode($obj);
    }

    if (is_object($obj) && isset($obj->title)) {

        $alt   = $obj->alt;
        $title = $obj->title;

        return "title='$title' alt='$alt'";
    }
}

function get_image_title_value($obj, $json = false)
{
    if (!$json && !is_object($obj)) {
        $obj = json_decode($obj);
    }

    if (is_object($obj) && isset($obj->title)) {

        return $obj->title;
    }

    return "";
}

function get_image_or_default($path, $no_img_name = "", $default_is_on_full_path = false)
{
    $logo_img = return_default_img($no_img_name,$default_is_on_full_path);

    if (isset($path) && !empty($path) && File::exists($path)) {
        $logo_img = url("/" . $path);
    }

    return $logo_img;
}

function getImageFromUrl($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    else {
        return url("public/images/no_image.png");
    }
}

function check_img_exist($path)
{
    if (isset($path) && !empty($path) && File::exists($path)) {
        return true;
    }

    return false;
}

function get_format_image($image_path, $get_dimensions)
{

    $path_file_arr = explode('/', $image_path);
    if (!count($path_file_arr)) {
        return $image_path;
    }
    $arr_length = count($path_file_arr);

    $file_name_with_extension = $path_file_arr[$arr_length - 1];


    $get_extension = explode('.', $file_name_with_extension);
    if (count($get_extension) != 2) {
        return $image_path;
    }
    $get_file_name_without_extension = $get_extension[0];
    $get_extension                   = $get_extension[1];

    $dimensions = explode(',', $get_dimensions);
    if (count($dimensions) != 2) {
        return $image_path;
    }

    $image_path_without_file = str_replace($file_name_with_extension, "", $image_path);

    $new_file_path    = $image_path_without_file . "" . $get_file_name_without_extension . "-" . $get_dimensions . "." . $get_extension;
    $check_file_exist = is_file(public_path("../" . $new_file_path));

    if ($check_file_exist == false) {
        $image = Image::make($image_path);

        $width  = $dimensions[0];
        $height = $dimensions[1];

        // case 1
        //$image->resize($width,$height)->save($new_file_path, 70);

        // case 2
        $image->width() > $image->height() ? $width = null : $height = null;
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($new_file_path, 70);

        return $new_file_path;
    }

    return $new_file_path;

}
