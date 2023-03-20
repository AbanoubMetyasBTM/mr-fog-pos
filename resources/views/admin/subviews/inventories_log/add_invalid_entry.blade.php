<?php


    $header_text    = "Add Invalid Entry ";

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/inventories-log/show-log")}}"> Inventories Logs</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">


            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <form id="save_form" class="ajax_form" action="{{url("admin/inventories-log/invalid-entry/" .$item_id)}}" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">Basic Data</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php

                                        $normal_fields = [
                                            "notes"
                                        ];

                                        $attrs = generate_default_array_inputs_html(
                                            $normal_fields,
                                            $data               = "",
                                            $key_in_all_fields  = "yes",
                                            $requried           = "required",
                                            $grid_default_value = 12
                                        );

                                        $attrs[3]["notes"]  = "textarea";

                                        echo generate_inputs_html_take_attrs($attrs);
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="Save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
