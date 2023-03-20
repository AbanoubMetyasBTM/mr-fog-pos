<style>
    hr{
        width: 100%;
        height:1px;
    }
</style>

<input type="hidden" class="go_to_site_content_keyword">

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/site-content/show_methods")}}">Site Content</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit <?=capitalize_string($content_title)?> ({{$current_lang->lang_title}})</li>
            </ol>
            <h6 class="slim-pagetitle">Edit <?=capitalize_string($content_title)?> ({{$current_lang->lang_title}})</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>


            <form class="ajax_form" action="<?= url("admin/site-content/edit_content/$method_lang_title/$content_id") ?>" method="POST" enctype="multipart/form-data">


                <?php if (isset($success)): ?>
                    <div class="alert alert-info" style="margin-top: 10px;margin-bottom: 10px;">
                        Success
                    </div>
                <?php endif; ?>


                <?php if (check_img_exist($img_path)): ?>
                    <img src="<?=url($img_path)?>" style="max-width:100%;" />
                <?php endif; ?>

                <?php if(count($select_tags) > 0): ?>
                    <div class="row select_inputs">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">Add Data</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php
                                        foreach ($select_tags as $key => $select) {

                                            echo generate_select_tags(
                                                $select["field_name"],
                                                $select["label_name"],
                                                $select["text"],
                                                $select["values"],
                                                $select["selected_value"],
                                                $select["class"],
                                                $select["multiple"],
                                                $select["required"],
                                                $select["disabled"],
                                                $content_data
                                            );

                                        }
                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(count($normal_tags) > 0): ?>
                    <div class="row normal_inputs">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">Add Data</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php
                                        $input_fields=  convert_inside_obj_to_arr($normal_tags,"field_name","array");
                                        $attrs = generate_default_array_inputs_html($input_fields,$content_data,true,"");

                                        foreach ($normal_tags as $key => $field) {
                                            if(isset($field["label_name"])&&!empty($field["label_name"])){
                                                $attrs[0][$field["field_name"]]=$field["label_name"];
                                            }
                                            if(isset($field["required"])){
                                                $attrs[2][$field["field_name"]]=$field["required"];
                                            }
                                            if(isset($field["field_type"])){
                                                $attrs[3][$field["field_name"]]=$field["field_type"];
                                            }
                                            if(isset($field["type"])){
                                                $attrs[3][$field["field_name"]]=$field["type"];
                                            }
                                            if(isset($field["class"])){
                                                $attrs[5][$field["field_name"]]=$field["class"];
                                            }

                                            $attrs[6][$field["field_name"]]="4";
                                            if(strpos($field["field_name"],"ckeditor")!==false){
                                                $attrs[5][$field["field_name"]].=" my_ckeditor";
                                                $attrs[6][$field["field_name"]]="12";
                                            }

                                        }

                                        echo
                                        generate_inputs_html_take_attrs($attrs);
                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(count($imgs_tags) > 0): ?>
                    <div class="row img_inputs">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">Add Data</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php foreach ($imgs_tags as $key => $img_field): ?>

                                        <?php
                                            $recommended_size = "";
                                            if($img_field["width"] >0 && $img_field["height"] >0){
                                                $recommended_size = "Recommended Width*Height (".$img_field["width"]."px*".$img_field["height"]."px)";
                                            }

                                            echo generate_img_tags_for_form_v2([
                                                "field_name"          => $img_field["field_name_without_brackets"],
                                                "required_field"      => $img_field["required"],
                                                "need_alt_title"      => "yes",
                                                "recomended_size"     => $recommended_size,
                                                "disalbed"            => "disabled",
                                                "display_label"       => "Upload New Image For "."<span class='text-danger'>".capitalize_string($img_field["field_name_without_brackets"])."</span>",
                                                "img_obj"             => $img_field["img"],
                                            ]);
                                        ?>

                                        <hr/>
                                    <?php endforeach; ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php foreach ($arr_tags as $key => $set): ?>
                    <div class="row array_inputs" id="{{$key}}_id">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">{{$key}}</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php
                                        $label_name = array();
                                        $field_name = array();
                                        $required = array();
                                        $type = array();
                                        $values = array();
                                        $class = array();
                                        $add_tiny_mce = array();
                                        $grid = array();
                                    ?>

                                    <?php foreach ($set as $key => $field): ?>

                                        <?php
                                            $label_name[]=capitalize_string($field["label_name"]);
                                            $field_name[]=$field["field_name"];
                                            $required[]="";
                                            $type[]=$field["field_type"];

                                            $old_values=array();

                                            if (isset($content_data->{$field["field_name"]})) {
                                                $old_values=$content_data->{$field["field_name"]};
                                            }


                                            $values[]=$old_values;
                                            $class[]=$field["field_class"]." form-control";
                                            $add_tiny_mce[]=$field["add_tiny_mce"];
                                            $grid[] = "12";
                                        ?>


                                    <?php endforeach; ?>

                                    <?php
                                        if(is_array($add_tiny_mce)){
                                            $add_tiny_mce=$add_tiny_mce[0];
                                        }

                                        echo generate_array_input(
                                            $label_name,
                                            $field_name,
                                            $required,
                                            $type,
                                            $values,
                                            $class,
                                            $add_tiny_mce,
                                            $default_values=array(),
                                            $data="",
                                            $grid
                                        );
                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


                <?php foreach ($slider_fields as $key => $field): ?>
                    <div class="row slider_inputs">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">{{$field["field_name"]}}</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>


                                <?php

                                    $slider_name="slider".($key+1);
                                    $slider_data=$content_data->$slider_name;

                                    $additional_inputs_arr=array();
                                    $attrs=array(array(),array(),array(),array(),array(),array());

                                    if(!empty($field["additional_inputs_arr"])&&!is_array($field["additional_inputs_arr"])){
                                        $field["additional_inputs_arr"]=explode(",",$field["additional_inputs_arr"]);
                                    }


                                    if (is_array($field["additional_inputs_arr"])&&  count($field["additional_inputs_arr"])) {
                                        $slider_old_data=array();
                                        if (isset($slider_data->other_fields)) {
                                            $slider_old_data=$slider_data->other_fields;
                                        }

                                        $attrs = generate_default_array_inputs_html(
                                            $input_fields=$field["additional_inputs_arr"]
                                            ,$slider_old_data
                                        );



                                        foreach ($attrs[1] as $key => $value) {
                                            $attrs[1][$key]=$value."[]";
                                            $attrs[3][$key]="textarea";

                                            if(strpos($key,'body')!==false){
                                                $attrs[5][$key]="my_ckeditor";
                                            }
                                        }

                                        foreach ($attrs[2] as $key => $value) {
                                            $attrs[2][$key]="";
                                        }

                                        for($k=0;$k<=6;$k++){
                                            $attrs[$k]=reformate_arr_without_keys($attrs[$k]);
                                        }

                                    }


                                    echo generate_slider_imgs_tags(
                                        $slider_photos=$slider_data->slider_objs,
                                        $field_name=$field["field_name"],
                                        $field_label=$field["label_name"]." Recommended Size width*height (".$field["width"]."px*".$field["height"]."px)",
                                        $field_id=$field["field_id"],
                                        $accept="image/*",
                                        $need_alt_title=$field["need_alt_title"],
                                        $need_alt_title=$field["need_alt_title"],
                                        $additional_inputs_arr=array($attrs[0], $attrs[1], $attrs[2], $attrs[3], $attrs[4], $attrs[5]),
                                        $show_as_link=false,
                                        $add_item_label="add",
                                        $without_attachment=true
                                    );


                                ?>


                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center col-md-12">
                    {{csrf_field()}}
                    <input type="submit" value="Save" name="submit" class="btn btn-info">
                </div>
            </form>

        </div>
    </div>
</div>
