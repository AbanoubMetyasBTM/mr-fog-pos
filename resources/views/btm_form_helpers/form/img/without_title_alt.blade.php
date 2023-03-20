<div class="generate_img_tags_for_form_without_title">
    <span class="label" for="">{!! $display_label !!} {!! $recomended_size !!}</span>

    <div class="show_img">
        <?php if(   strpos($old_path_value, "pdf") > 0 || strpos($old_path_value, "doc") > 0 || strpos($old_path_value, "docx") > 0 || strpos($old_path_value, "mp4") > 0 ):?>
            <a class="btn btn-info" href="{{$old_path_value}}" >link</a>
        <?php else: ?>
            <img src="{{get_image_or_default($old_path_value,"upload_image.png")}}" alt="" class="show_intial_img">
        <?php endif; ?>

        <input type="file" class="file_upload_input" id="{{$filed_name_id}}" name="{{$filed_name}}" {!! $required_field !!}>
        <span class="input_file_name">Upload</span>
    </div>

</div>

<div style="display: none;">
    <input class="checkbox_field_image" type="checkbox" name="{{$checkbox_field_name}}" id="{{$checkbox_field_name_id}}">:upload new file
</div>

