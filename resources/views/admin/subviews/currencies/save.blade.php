<?php
    /**
     * @var $item_data object
     */

    $header_text = "New Currency";
    $id          = "";

    if (is_object($item_data)) {
        $header_text = front_tf($item_data->currency_name);
        $id          = $item_data->currency_id;
    }

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/currencies")}}">Currencies</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">Currencies</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/currencies/save/$id")}}" method="POST"
                      enctype="multipart/form-data">

                    @include("general_form_blocks.main_form")

                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="Save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




