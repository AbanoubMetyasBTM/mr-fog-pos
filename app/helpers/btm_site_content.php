<?php

use App\btm_form_helpers\site_content;

function get_adv($adv_obj, $img_width = "0px", $img_height = "0px")
{

    if (is_array($adv_obj) && isset($adv_obj[0])) {
        $adv_obj = $adv_obj[0];
    }

    if (!isset($adv_obj->ad_show)) {
        return "";
    }

    if ($adv_obj->ad_show == "script") {
        return $adv_obj->ad_script;
    }
    else {
        return "<a href='$adv_obj->ad_link'>
                        <img class='responsive-img' src='" . url("/" . $adv_obj->ad_img_path) . "' alt='$adv_obj->ad_img_alt' title='$adv_obj->ad_img_title' style='width:$img_width;height:$img_height;' />
                    </a>";
    }

}

function showContentBg($imgField, $defaultImg = "")
{

    $filedIsImg = true;
    if (checkAdminCanSeeSiteContentLinks()) {
        $filedIsImg = "bg";
    }

    $returnHtml = "background-image: url('" . showContent($imgField, $filedIsImg, $defaultImg) . "');";

    if ($filedIsImg === "bg") {
        $returnHtml .= "background-btm: url('" . showContent($imgField, true, $defaultImg) . "');";
    }

    return $returnHtml;

}

function getSliderMainLanguageContent($field)
{

    $mainLangData = Cache::get("$field." . getMainLangTitle());

    if ($mainLangData != null) {
        return $mainLangData;
    }

    $fieldArr = explode(".", $field);
    if (!isset_and_array($fieldArr) || count($fieldArr) != 2) {
        return;
    }

    site_content::general_get_content(
        getMainLangTitle(),
        [
            $fieldArr[0]
        ],
        [
            "{$fieldArr[0]}" => [$fieldArr[1]]
        ]
    );

    $sliderData = $GLOBALS["site_content"][$fieldArr[0]]->{$fieldArr[1]} ?? "";

    if (!is_object($sliderData)) {
        return "";
    }

    return $sliderData;

}

function cacheContentForSlider($field)
{

    $fieldArr = explode(".", $field);
    if (!isset_and_array($fieldArr) || count($fieldArr) != 2) {
        return;
    }

    site_content::general_get_content(
        getCurrentLangTitle(),
        [
            $fieldArr[0]
        ],
        [
            "{$fieldArr[0]}" => [$fieldArr[1]]
        ]
    );

    $mainLangSliderData = getSliderMainLanguageContent($field);
    $sliderData         = $GLOBALS["site_content"][$fieldArr[0]]->{$fieldArr[1]} ?? "";

    if (!is_object($sliderData)) {
        return "";
    }

    $sliderData->imgs = $mainLangSliderData->imgs;

    return $sliderData;

}

function cacheContentForArray($field, $setFields, $defaultValues)
{

    $fieldArr = explode(".", $field);

    if (!isset_and_array($fieldArr) || count($fieldArr) != 2) {
        return;
    }

    site_content::general_get_content(
        getCurrentLangTitle(),
        [
            $fieldArr[0]
        ]
    );

    $siteContent = $GLOBALS["site_content"][$fieldArr[0]];
    $setData     = [];

    foreach ($setFields as $setField){
        if(!isset($siteContent->{$setField}))continue;
        $setData[$setField] = $siteContent->{$setField};
    }

    if(count($setData) == 0){
        site_content::addArrayFieldToMethod($fieldArr[0],$fieldArr[1],$setFields,$defaultValues);
        return $defaultValues;
    }

    return $setData;

}

function getContentForSlider($field, $returnOriginalImages = false)
{

    $mainLangData    = Cache::get("$field." . getMainLangTitle());
    $CurrentLangData = Cache::get("$field." . getCurrentLangTitle());

    if ($CurrentLangData == null || $mainLangData == null) {
        //it will cache both languages (main language and current language)
        return cacheContentForSlider($field);
    }


    if (!$returnOriginalImages){

        if(isset($mainLangData->imgs))
        {
            $CurrentLangData->imgs = $mainLangData->imgs;
        }
        else if(isset($mainLangData->img_ids))
        {
            $CurrentLangData->imgs = $mainLangData->img_ids;
        }

    }

    return $CurrentLangData;

}

function getContentForArr($field, $setFields = [], $defaultValues = [])
{

    $CurrentLangData = Cache::get("$field." . getCurrentLangTitle());

    if ($CurrentLangData == null && count($setFields) > 0) {
        return cacheContentForArray($field, $setFields, $defaultValues);
    }


    return $CurrentLangData;

}

function getLangTitleForCache($field_is_img = false)
{

    if ($field_is_img) {
        return config("default_language.main_lang_title");
    }

    return config("default_language.primary_lang_title");

}

function getMainLangTitle()
{

    return config("default_language.main_lang_title");

}

function getCurrentLangTitle()
{

    return config("default_language.primary_lang_title");

}

function showContentALtTitle($field)
{
    $data = Cache::get("$field." . getLangTitleForCache(true));

    if ($data !== null && !empty($data)) {
        return get_image_alt_title($data);
    }

    return "";

}

function showContentOnlyWithoutAdminStuff($field){

    return Cache::get("$field." . getLangTitleForCache(false));

}


/**
 * @param $field string general_keywords.save
 * @param bool $field_is_img
 * @param string $defaultImg defines default image path
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */

function showContent($field, $field_is_img = false, $defaultImg = "")
{

    //get keyword from cache directly
    $data = Cache::get("$field." . getLangTitleForCache($field_is_img));

    if ($data !== null && !checkAdminCanSeeSiteContentLinks()) {

        if ($field_is_img) {

            if($data->path == "" && !empty($defaultImg)){
                $data->path = $defaultImg;
            }

            return get_image_from_json_obj($data,$defaultImg);
        }

        return $data;
    }

    //handel keyword wrong format
    $fieldArr = explode(".", $field);
    if (!isset_and_array($fieldArr) || count($fieldArr) != 2) {
        return capitalize_string($field);
    }

    //get keyword from parent keywords collection
    return editContentWrapper($fieldArr, $field_is_img, $defaultImg);

}

function checkAdminCanSeeSiteContentLinks()
{

    //check if he is admin or not
    if (Session::get("show_admin_content") == "yes") {
        return true;
    }

    return false;

}

function editContentWrapper($fieldArr, $field_is_img, $defaultImg)
{


    if (!checkAdminCanSeeSiteContentLinks()) {
        return show_content_v2($fieldArr[0], $fieldArr[1], $field_is_img, $defaultImg);
    }

    if ($field_is_img === false) {
        return
            "<span class='editContentWrapper'>" .
            show_content_v2($fieldArr[0], $fieldArr[1], $field_is_img, $defaultImg) .
            adminEditContent($fieldArr[0], $fieldArr[1]) .
            "</span>";
    }
    elseif ($field_is_img === true) {
        return show_content_v2($fieldArr[0], $fieldArr[1], $field_is_img, $defaultImg) . "?
        show_admin_content=yes&site_content_url=" . getAdminEditContentLink($fieldArr[0], $fieldArr[1]);
    }
    elseif ($field_is_img === "bg") {
        return show_content_v2($fieldArr[0], $fieldArr[1], $field_is_img, $defaultImg);
    }

}

function adminEditContent($content_json, $field_name)
{


    return "<span class='do_not_ajax admin_edit_content' href='" . getAdminEditContentLink($content_json, $field_name) . "'><i class='fa fa-edit'></i></a>";

}

function getAdminEditContentLink($content_json, $field_name = "")
{

    $lang_url_segment = Session::get('lang_url_segment');
    if ($lang_url_segment == "/") {
        $lang_url_segment = config("default_language.main_lang_title") . "/";
    }

    return url("/admin/site-content/edit_content/{$lang_url_segment}$content_json?go_to_keyword=$field_name");

}


function show_content_v2($content_json, $field_name, $field_is_img = false, $defaultImg = "")
{

    //load site content parent collection first and cache each item at it to load it directly for next requests
    if (!isset($GLOBALS["site_content"]) || !isset($GLOBALS["site_content"][$content_json])) {

        site_content::general_get_content(
            getCurrentLangTitle(),
            [$content_json]
        );

    }

    //check if the parent collection is exist or not , and if not this code will added it
    if (!isset($GLOBALS["site_content"][$content_json]->{$field_name})) {

        if (env("do_not_add_site_content_at_local", false) == false) {
            site_content::addNormalFieldToMethod($content_json, $field_name, $field_is_img ? "imgs_fields" : "input_fields");
        }

        if (!$field_is_img) {
            if (env("do_not_add_site_content_at_local", false) == false) {
                site_content::addNormalFieldValue($content_json, $field_name);
            }

            return capitalize_string($field_name);
        }

        if (!empty($defaultImg)) {
            return url($defaultImg);
        }
        return url('/public/images/no_image.png');
    }

    return real_show_content($content_json, $GLOBALS["site_content"][$content_json], $field_name, $field_is_img, $defaultImg);
}

//please don't use it anymore use v2
function real_show_content($method_name, $content_json, $field_name, $field_is_img = false, $defaultImg = "")
{

    if (isset($content_json->{$field_name})) {
        if ($field_is_img) {

            if (!isset($content_json->{$field_name}->path)) {
                return url($defaultImg);
            }

            if (!file_exists($content_json->{$field_name}->path) && !empty($defaultImg)) {
                return url($defaultImg);
            }

            return get_image_or_default($content_json->{$field_name}->path);
        }
        else {

            if(empty($content_json->{$field_name})){
                return capitalize_string($field_name);
            }

            return $content_json->{$field_name};
        }
    }
    else {

        if (env("do_not_add_site_content_at_local", false) == false) {
            site_content::addNormalFieldToMethod($method_name, $field_name, $field_is_img ? "imgs_fields" : "input_fields");
        }

        if ($field_is_img) {

            if (!empty($defaultImg)) {
                return url('/public/default_images/' . $defaultImg);
            }

            return url('/public/images/no_image.png');
        }
        else {
            return capitalize_string($field_name);
        }
    }
}

//for slider other fields
function show_content_for_other_fields($other_fields, $field_name, $key)
{

    if (!isset($other_fields->{$field_name}) || !isset($other_fields->{$field_name}[$key])) {
        return capitalize_string($field_name);
    }

    return $other_fields->{$field_name}[$key];
}

function extract_youtube_links($content, $change = "yes")
{
    if (preg_match_all('~(https://www\.youtube\.com/watch\?v=[%&=#\w-]*)~', $content, $m)) {
        if ($change == "yes") {
            $output = array();
            foreach ($m[0] as $key => $value) {
                $value    = str_replace("watch", "embed", $value);
                $output[] = str_replace("?v=", "/", $value);
            }
            return ($output);
        }
        return $m[0];
    }
}

function return_youtube_thumbnail($youtube_link = "")
{
    //https://img.youtube.com/vi/<insert-youtube-video-id-here>/0.jpg
    $youtube_code = explode("=", $youtube_link);
    if (isset_and_array($youtube_code)) {
        $youtube_code = $youtube_code[1];

        return "https://img.youtube.com/vi/$youtube_code/0.jpg";
    }
    return "";
}

function convert_youtube_link_to_lazy_frame($youtube_link = "", $width = "", $height = "")
{
    $embed = extract_youtube_links($youtube_link);
    if (isset_and_array($embed)) {
        $embed = $embed[0];

        return '<iframe width="' . $width . '" height="' . $height . '" class="lazy1"
                    src="' . $embed . '"
                    frameborder="0"
                    allowfullscreen>
                </iframe>';
    }
    return "";
}

function draw_stars($value, $stars_count = 5)
{

    $int_val     = (int)$value;
    $return_html = "";

    for ($i = 0; $i < $int_val; $i++) {
        $return_html .= '
            <i class="fa fa-star fa fa-star"></i>
        ';
    }

    if ($value > $int_val) {
        $return_html .= '
            <i class="fa fa-star-half fa fa-star-half"></i>
         ';
    }

    $rest_val     = $stars_count - $value;
    $rest_int_val = (int)$rest_val;


    for ($i = 0; $i < $rest_int_val; $i++) {
        $return_html .= '
            <i class="fa fa-star-o fa fa-star-o"></i>
        ';
    }

    return $return_html;
}

