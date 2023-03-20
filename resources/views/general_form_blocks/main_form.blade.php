<?php
/** @var object $item_data  */
/** @var \App\form_builder\FormBuilder $builderObj  */
/** @var \Illuminate\Support\Collection $all_langs  */
?>

@include("general_form_blocks.main_form_components.select_and_normal_fields")
@include("general_form_blocks.main_form_components.translate_fields")
@include("general_form_blocks.main_form_components.save_arr_fields")

<?php
    echo draw_img_fields(
        $builderObj,
        $item_data
    );
?>

