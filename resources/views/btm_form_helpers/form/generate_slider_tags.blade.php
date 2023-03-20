<div class="generate_slider_tags">

    <div class="modal fade edit_img_modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit</h4>
                </div>
                <div class="modal-body">


                    <input type="hidden" class="edit_img_id" value="">

                    <div class="form-group">
                        <label for="">Edit  {{$show_as_link?"File":"Image"}}</label>
                        <input type="file" class="edit_file_at_slider form-control" name="edit_file_at_slider" accept="{{$accept}}">
                    </div>

                    <div class="upload_new_item_msg"></div>

                    <button class="btn btn-info edit_img_at_slider_btn">Save</button>

                </div>
            </div>
        </div>
    </div>

    <?php
        $field_name_arr=$field_name."[]";
        $alt_field=$field_name."_alt[]";
        $title_field=$field_name."_title[]";

        $old_alt_field=$field_name."_edit_alt[]";
        $old_title_field=$field_name."_edit_title[]";


        $slider_photos_ids=array();

        if (is_array($slider_photos)&&  count($slider_photos)) {

            if ($without_attachment){
                $slider_photos_ids=$slider_photos;
            }
            else{
                $slider_photos_ids=convert_inside_obj_to_arr($slider_photos, "id");
            }

        }

        $json_values_of_slider_id="json_values_of_slider_id".$field_name;
        $json_values_of_slider="json_values_of_slider".$field_name;
    ?>

    <?php if($need_alt_title=="yes" || (is_array($additional_inputs_arr)&&count($additional_inputs_arr))): ?>
        @include("btm_form_helpers.form.slider.all_inputs")
    <?php else: ?>
        <!--  works only with ajax form -->
        @include("btm_form_helpers.form.slider.just_imgs")
    <?php endif; ?>

</div>

