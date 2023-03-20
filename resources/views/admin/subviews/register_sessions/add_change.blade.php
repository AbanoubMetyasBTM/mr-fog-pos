<?php


    $header_text        = "Add Change";

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/registers")}}">Register Session</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <label class="section-title">Add Change</label>

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/register-sessions/add-change/$register_id")}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php

                                        $normal_fields = [
                                            "change_amount"
                                        ];

                                        $attrs = generate_default_array_inputs_html(
                                            $normal_fields,
                                            $data               = "",
                                            $key_in_all_fields  = "yes",
                                            $requried           = "required",
                                            $grid_default_value = 12
                                        );

                                        $attrs[3]["change_amount"]  = "number";

                                        echo generate_inputs_html_take_attrs($attrs);
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




