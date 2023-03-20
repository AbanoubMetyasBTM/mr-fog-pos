<div class="row">
    <div class="col-md-12">
        <label for="">{!! $display_label !!} {!! $recomended_size !!}</label>
    </div>

    <?php
    $file_size_class = "col-md-4";
    if ($need_alt_title != "yes") {
        $file_size_class = "col-md-12";
    }
    ?>

    <div class="{{$file_size_class}}">
        <div class="custom-file">
            <input type="file" class="custom-file-input file_upload_input" id="{{$filed_name_id}}" name="{{$filed_name}}"   {!! $required_field !!}>
            <label class="custom-file-label custom-file-label-inverse input_file_name" for="customFile">Select file</label>
        </div><!-- custom-file -->
    </div>


    <?php if($need_alt_title == "yes"):?>
    <div class="col-md-4">
        <input type="text" class="form-control" placeholder="image title" name="{{$title_field_name}}" {{$required_alt_title}} value="{{$old_title_value}}">
    </div>
    <div class="col-md-4">
        <input type="text" class="form-control" placeholder="image alt" name="{{$alt_field_name}}" {{$required_alt_title}} value="{{$old_alt_value}}">
    </div>
    <?php endif;?>
</div>

<?php if($disalbed != ""):?>
<div class="row">
    <div class="col-md-12">
        <?php if(   strpos($old_path_value, "pdf") > 0 ||
                    strpos($old_path_value, "doc") > 0 ||
                    strpos($old_path_value, "docx") > 0 ||
                    strpos($old_path_value, "mp4") > 0
        ):?>
        <a class="btn btn-info" href="{{$old_path_value}}" >link</a>
        <?php else: ?>
        <div class="wd-sm-200">
            <figure class="overlay preview_figure_img">
                <img src="{{get_image_or_default($old_path_value)}}"
                     alt="{{$old_alt_value}}"
                     title="{{$old_title_value}}"
                     class="img-fluid"
                     style="width: auto; height: 100px;">
                <figcaption class="overlay-body d-flex align-items-end justify-content-center">
                    <div class="img-option img-option-sm">
                        <a href="{{get_image_or_default($old_path_value)}}"
                           target="_blank" class="img-option-link">
                            <div><i class="fa fa-download"></i></div>
                        </a>
                        <a href="#" class="img-option-link upload_new_file">
                            <div><i class="icon ion-edit"></i></div>
                        </a>
                    </div>
                </figcaption>
            </figure>
        </div><!-- wd-300 -->
        <?php endif;?>
    </div>
</div>
<div class="row" style="display: none;">
    <input class="checkbox_field_image" type="checkbox" name="{{$checkbox_field_name}}" id="{{$checkbox_field_name_id}}">:upload new file
</div>
<?php endif;?>
