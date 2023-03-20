<?php

    /**
     * @var $item_data object
    */

    $header_text        = "Add New";
    $item_id            = "";

    if (is_object($item_data)) {
        $header_text    = "Edit ".front_tf($item_data->pro_name);
        $item_id        = $item_data->pro_id;
    }

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/products")}}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <?php if(havePermission("admin/products","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/products/save")}}"> Add New <i class="fa fa-plus"></i></a>
                </label>
            <?php endif; ?>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/products/save/$item_id")}}" method="POST" enctype="multipart/form-data">

                    @include("general_form_blocks.main_form")

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20 variation_types_section">
                                <label class="section-title">Variations</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                @include("admin.subviews.products.save_product_components.old_variants")
                                @include("admin.subviews.products.save_product_components.new_variants")

                            </div>
                        </div>
                    </div>


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




