<div class="slim-mainpanel">
    <div class="container-fluid">

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <style>
                    hr{
                        width: 100%;
                        height:1px;
                    }
                </style>

                <?php
                    $header_text="Add New Permission Page";
                    $per_page_name="";
                    if ($permission_page_data!="") {
                        $header_text="Edit ".$permission_page_data->page_name;
                        $per_page_name=$permission_page_data->page_name;
                    }
                ?>



                <div class="panel panel-info">
                    <div class="panel-heading"><?=$header_text?></div>
                    <div class="panel-body ">
                        <form id="save_form" class="ajax_form row" action="<?=url("dev/permissions/permissions_pages/save/".string_safe($per_page_name))?>" method="POST" enctype="multipart/form-data">

                            <?php
                                $normal_tags=array("page_name","sub_sys");

                                $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $permission_page_data,
                                    "yes",
                                    "required",
                                    "6"
                                );

                                echo generate_inputs_html_take_attrs($attrs);

                                echo generate_select_tags_v2([
                                    "field_name" => "show_in_admin_panel",
                                    "label_name" => "Show in admin panel",
                                    "text"       => ["Yes", "No"],
                                    "values"     => ["1", "0"],
                                    "class"      => "form-control",
                                    "data"       => $permission_page_data,
                                    "grid"       => "col-md-6",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name" => "hide_accept_buttons",
                                    "label_name" => "Hide accept buttons",
                                    "text"       => ["No", "Yes"],
                                    "values"     => ["0", "1"],
                                    "class"      => "form-control",
                                    "data"       => $permission_page_data,
                                    "grid"       => "col-md-6",
                                ]);


                            ?>

                            <?php

                                echo generate_array_input(
                                    $label_name=["Add Another Permission"],
                                    $field_name=["all_additional_permissions"],
                                    $required=[""],
                                    $type=["text"],
                                    $values=[""],
                                    $class=["form-control"],
                                    $add_tiny_mce="",
                                    $default_values=array(),
                                    $data=$permission_page_data,
                                    [
                                        "all_additional_permissions"=>"12"
                                    ]
                                );

                            ?>


                            {{csrf_field()}}
                            <input type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
