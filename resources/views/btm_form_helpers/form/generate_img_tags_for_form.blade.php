<?php

if (is_object($img_obj)) {
    $old_path_value = $img_obj->path;
    $old_title_value = $img_obj->title;
    $old_alt_value = $img_obj->alt;
}

if (!empty($old_path_value)){
    $disalbed = "disabled";
}


$filed_name_id = $filed_label . "id";

$checkbox_field_name_id = $checkbox_field_name . "id";

$title_field_name = $filed_label . "title";
$alt_field_name = $filed_label . "alt";

?>

<div class="{{($need_alt_title == "yes")?"col-md-12":"col-md-4"}} {{$grid}} form-group parent_file_upload_input">

    <?php if($need_alt_title == "yes" || $need_alt_title == "not_really"):?>
        @include("btm_form_helpers.form.img.with_title_alt")
    <?php else: ?>
        @include("btm_form_helpers.form.img.without_title_alt")
    <?php endif; ?>

</div>


