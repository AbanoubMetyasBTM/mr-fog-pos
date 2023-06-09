<?php

    $header_text    = "Add New";
    $item_id        = "";

    if (is_object($item_data)) {
        $header_text    = "Edit ".$item_data->group_name;
        $item_id        = $item_data->group_id;
    }

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/taxes-groups")}}">Taxes Groups</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <label class="section-title">
                <?php if(havePermission('admin/taxes_groups','add_action')): ?>
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/taxes-groups/save")}}"> Add New <i class="fa fa-plus"></i></a>
                <?php endif; ?>
            </label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/taxes-groups/save/$item_id")}}" method="POST" enctype="multipart/form-data">


                    @include("general_form_blocks.main_form")


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>


                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




