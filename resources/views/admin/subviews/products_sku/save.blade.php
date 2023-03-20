<?php

    /**
     * @var $item_data object
    */


?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products")}}">Products</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products/save/$product_obj->pro_id")}}">{{$product_obj->pro_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products-sku/show/$product_obj->pro_id")}}">SKUs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Sku Images</li>
            </ol>
            <h6 class="slim-pagetitle">Edit Sku Images</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/products-sku/save/$item_data->ps_id")}}" method="POST" enctype="multipart/form-data">

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




