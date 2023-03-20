<?php
    /** @var App\form_builder\FormBuilder $builder */
    /** @var Eloquent $data_object */
?>

<?php
    foreach ($builder->img_fields as $field=>$attrs){

        $display_label = $attrs["display_label"];
        if(
            isset($attrs["height"])&&$attrs["height"]>0&&
            isset($attrs["width"])&&$attrs["width"]>0
        ){
            $display_label .= " - " ." width  * height " . "  ( ".$attrs["width"]." * ".$attrs["height"]." ) ";
        }

        echo generate_img_tags_for_form(
            $filed_name=$field."_file",
            $filed_label=$field."_file",
            $required_field="",
            $checkbox_field_name=$field."_file_checkbox",
            $need_alt_title=(isset($attrs["need_alt_title"])?$attrs["need_alt_title"]:"no"),
            $required_alt_title="",
            $old_path_value="",
            $old_title_value="",
            $old_alt_value="",
            $recomended_size="",
            $disalbed="",
            $displayed_img_width="50",
            $display_label,
            isset($data_object->{$field})?$data_object->{$field}:""
        );
    }
?>

<?php if(count($builder->slider_fields)): ?>
    <div class="col-md-12">
        <hr class="my_hr">
    </div>
<?php endif; ?>

<div class="col-md-12">

<?php
    foreach ($builder->slider_fields as $field=>$attrs){

        if(
            isset($attrs["height"])&&$attrs["height"]>0&&
            isset($attrs["width"])&&$attrs["width"]>0
        ){
            $attrs["display_label"] .= " "." width * height (".$attrs["width"]." * ".$attrs["height"].")";
        }

        if(isset($attrs["imgs_limit"])&&$attrs["imgs_limit"]>0){
            $attrs["display_label"] .= " - "."images count limit: "."(".$attrs["imgs_limit"].")";
        }

        $sliderImgs      = "";
        $otherInputsData = "";
        if(isset($data_object->{$field})){
            $sliderData = $data_object->{$field};
        }

        if (isset($sliderData->slider_objs)) {
            $sliderImgs      = $sliderData->slider_objs;
        }

        if (isset($sliderData->other_fields)) {
            $otherInputsData = $sliderData->other_fields;
        }


        $additionalInputs = [];
        if(isset($attrs["additional_inputs"])){

            $additionalInputs = generate_default_array_inputs_html($attrs["additional_inputs"]["fields"],$otherInputsData,true,"");
            $additionalInputs = reformate_arr_without_keys($additionalInputs);


            foreach ($additionalInputs[1] as $key => $value) {
                $additionalInputs[1][$key] = $value . "[]";
                $additionalInputs[3][$key] = "textarea";

                if (strpos($key, 'body') !== false) {
                    $additionalInputs[5][$key] = "my_ckeditor";
                }
            }

            for ($k = 0; $k <= 6; $k++) {
                $additionalInputs[$k] = reformate_arr_without_keys($additionalInputs[$k]);
            }

        }


        echo generate_slider_imgs_tags(
            $slider_photos=$sliderImgs,
            $field_name=$field."_file",
            $field_label=$attrs["display_label"],
            $field_id=$field."_file_id",
            $accept="image/*",
            $need_alt_title=(isset($attrs["need_alt_title"])?$attrs["need_alt_title"]:"no"),
            $need_alt_title=(isset($attrs["need_alt_title"])?$attrs["need_alt_title"]:"no"),
            $additional_inputs_arr=$additionalInputs,
            $show_as_link=false,
            $add_item_label="add",
            $without_attachment=true
        );

        echo "<hr class='my_hr'>";
    }

?>
</div>
