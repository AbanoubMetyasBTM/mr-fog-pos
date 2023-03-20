<?php
    $field_name_arr = str_replace("[","",$field_name_arr);
    $field_name_arr = str_replace("]","",$field_name_arr);
?>

<div class="form-group slider_just_imgs">
    <div class="row-fluid">

        <div class="slider_imgs_class">

            <div class="row item">

                <div class="col-md-12">
                    <label>{{$field_label}}</label>
                    <input type="file" class="form-control just_img_file_uploader {{$field_id}}_class" multiple data-name="{{$field_name_arr}}" id="{{$field_id}}" accept="{{$accept}}">
                </div>

                <div class="preview_imgs width_100">

                    <div class="new_img"></div>


                    <?php if(is_array($slider_photos)&&count($slider_photos)): ?>
                        <?php foreach($slider_photos as $key => $img): ?>
                            <div class="preview_post_img_div old_item">
                                <img src="{{get_image_or_default($img->path)}}" class="preview_post_img slider_item_when_edited_{{$img->id}}" data-item_type="img">

                                <div class="remove_img">
                                    <button type="button" class="remove_img_from_arr open_edit_modal" data-img_id="{{$without_attachment?$img->path:$img->id}}">
                                        <i class="fa fa-edit la la-edit"></i>
                                    </button>

                                    <a href="#" class="remove_img_from_arr slider_img_remover"  {!! $without_attachment?'data-without_attachment="yes"':"" !!} data-photoid="{{$img->id}}">
                                        <i class="fa fa-times la la-times"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

            </div>
        </div>




    </div>
    <input type="hidden" class="json_values_of_slider_class" name="{{$json_values_of_slider}}" value='{!! json_encode($slider_photos_ids) !!}'>
</div>
