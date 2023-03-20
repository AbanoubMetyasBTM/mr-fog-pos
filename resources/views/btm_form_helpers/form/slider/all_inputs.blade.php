<div class="form-group">
    <div class="row-fluid">

        <div class="slider_imgs_class">

            <div class="row item">
                <div class="col-md-6">
                    <label>{{$field_label}}</label>
                    <input type="file" class="form-control {{$field_id}}_class" name="{{$field_name_arr}}" id="{{$field_id}}" accept="{{$accept}}">
                </div>

                <div class="col-md-6">

                    <?php if($need_alt_title=="yes"): ?>

                    <div class="col-md-12">
                        <label>Title</label>
                        <input type="text" class="form-control" name="{{$title_field}}" placeholder="Title">
                    </div>

                    <div class="col-md-12">
                        <label>Alt</label>
                        <input type="text" class="form-control" name="{{$alt_field}}" placeholder="logo Alt">
                    </div>

                    <?php endif; ?>

                    <?php if(is_array($additional_inputs_arr)&&count($additional_inputs_arr)): ?>
                        <?php
                            //add additional fields
                            $empty_values=array();
                            for($i=0;$i<count($additional_inputs_arr[0]);$i++){
                                $empty_values[]="";
                            }



                            echo generate_inputs_html(
                                $labels_name=$additional_inputs_arr[0],
                                $fields_name=$additional_inputs_arr[1],
                                $required=$additional_inputs_arr[2],
                                $type=$additional_inputs_arr[3],
                                $values=$empty_values,
                                $class=$additional_inputs_arr[5]
                            );
                        ?>
                   <?php endif; ?>
                </div>


            </div>
        </div>

        <div class="col-md-12 text-center my-2">
            <a href="" class="btn btn-primary add_img_btn_class">{{$add_item_label}}</a>
        </div>

        <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">

            <div id="accordion2" class="accordion-one accordion-one-primary" role="tablist" aria-multiselectable="true">
                <div class="card">
                    <div class="card-header" role="tab" id="headingOne2">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#old_data_{{$field_name}}" aria-expanded="true" aria-controls="old_data_{{$field_name}}" class="tx-gray-800 transition">
                            Current {{$show_as_link?"Files":"Images"}}
                        </a>
                    </div><!-- card-header -->

                    <div id="old_data_{{$field_name}}" class="collapse show" role="tabpanel" aria-labelledby="headingOne2">
                        <div class="old_imgs card-body">
                            <?php if(is_array($slider_photos)&&count($slider_photos)): ?>

                            <ul style="list-style: none; display: inline-block;width: 100%;">
                                <?php foreach($slider_photos as $key => $img): ?>

                                <li class="old_item col-md-12" style="border-bottom: 1px solid #CCC;padding-bottom: 5px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if($show_as_link==true): ?>
                                            <a target="_blank" href="{{url("$img->path")}}" class="slider_item_when_edited_{{$img->id}}" data-item_type="link">
                                                {{((!empty($img->title))?$img->title:"Link")}}
                                            </a>
                                            <?php else: ?>
                                            <img src="{{get_image_or_default($img->path)}}"
                                                 alt="{{$img->alt}}" title="{{$img->title}}"
                                                 class="slider_item_when_edited_{{$img->id}}" data-item_type="img"
                                                 style="width:200px;float: right">
                                            <?php endif; ?>

                                            <div class="col-md-12" style="padding-left: 0px;padding-left: 0px;">
                                                <button type="button" class="btn btn-info open_edit_modal" data-img_id="{{$without_attachment?$img->path:$img->id}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="#" class="btn btn-danger slider_img_remover" {!! $without_attachment?'data-without_attachment="yes"':"" !!} data-photoid="{{$img->id}}">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <?php if($need_alt_title=="yes"): ?>
                                            <div class="col-md-12 form-group">
                                                <label for="">Title</label>
                                                <input type="text" class="form-control slider_img_title" name="{{$old_title_field}}" placeholder="Slider Title" value="{{$img->title}}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label for="">Alt</label>
                                                <input type="text" class="form-control slider_img_alt" name="{{$old_alt_field}}" placeholder="Slider Alt" value="{{$img->alt}}">
                                            </div>
                                            <?php endif; ?>

                                            <?php if(is_array($additional_inputs_arr)&&count($additional_inputs_arr)): ?>
                                                <?php
                                                    //add additional fields
                                                    $new_values=array();

                                                    foreach ($additional_inputs_arr[4] as $input_v_key => $input_v) {

                                                        if (isset($input_v[$key])) {
                                                            //$new_values[]=  array_shift($input_v);
                                                            $new_values[]=  $input_v[$key];

                                                        }
                                                        else{
                                                            $new_values[]="";
                                                        }
                                                    }


                                                    foreach ($additional_inputs_arr[1] as $field_key => $value) {
                                                        if ($key==0) {
                                                            $additional_inputs_arr[1][$field_key]="edit_".$additional_inputs_arr[1][$field_key];
                                                        }

                                                    }

                                                    echo generate_inputs_html(
                                                        $labels_name=$additional_inputs_arr[0],
                                                        $fields_name=$additional_inputs_arr[1],
                                                        $required=$additional_inputs_arr[2],
                                                        $type=$additional_inputs_arr[3],
                                                        $values=$new_values,
                                                        $class=$additional_inputs_arr[5]
                                                    );
                                                ?>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>

                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
    <input type="hidden" class="json_values_of_slider_class" name="{{$json_values_of_slider}}" value='{!! json_encode($slider_photos_ids) !!}'>
</div>
