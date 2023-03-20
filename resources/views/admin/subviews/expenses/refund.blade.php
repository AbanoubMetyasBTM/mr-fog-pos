<?php
    $header_text  = "Add Refund";
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("$show_transaction_log_link")}}">Expenses</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">



                <form id="save_form" class="ajax_form" action="{{url("admin/expenses/refund/$item_id")}}" method="POST" enctype="multipart/form-data">

                    <?php

                        $normal_tags = [
                            "transaction_notes"
                        ];

                        $attrs                    = generate_default_array_inputs_html(
                            $fields_name          = $normal_tags,
                            $data                 = $request_data ?? "",
                            $key_in_all_fields    = "yes",
                            $required             = "required",
                            $grid_default_value   = 12
                        );

                        $attrs[3]["transaction_notes"]    = "textarea";


                        echo
                        generate_inputs_html_take_attrs($attrs);

                    ?>


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




