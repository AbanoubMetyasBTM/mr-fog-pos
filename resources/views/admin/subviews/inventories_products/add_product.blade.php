<?php
    $header_text        = "Add Product To Inventory";
?>


<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/inventories-products/show")}}">Inventories Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/inventories-products/add-product")}}" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <?php

                            echo generate_select_tags_v2([
                                "field_name" => "inventory_id",
                                "label_name" => "Select Inventory Name",
                                "text"       => $all_inventories->pluck("inv_name")->all(),
                                "values"     => $all_inventories->pluck("inv_id")->all(),
                                "class"      => "form-control select2_search",
                                "data"       => $request_data ?? "",
                                "grid"       => "col-md-6",
                            ]);

                            generateBTMSelect2([
                                "field_name"       => "pro_sku_id",
                                "label_name"       => "Product Barcode",
                                "data-placeholder" => "Search by product barcode",
                                "data-url"         => langUrl("admin/products-sku/get-product-sku-by-barcode"),
                                "class"            => "form-control convert_select_to_btm_select2",
                                "data"             => $request_data ?? "",
                                "hide_label"       => false,
                                "grid"             => "col-md-6",
                            ]);

                            $normal_tags = [
                                "ip_box_quantity", "ip_item_quantity", "limit_quantity"
                            ];

                            $attrs = generate_default_array_inputs_html(
                                $fields_name = $normal_tags,
                                $data = $request_data ?? "",
                                $key_in_all_fields = "yes",
                                $required = "",
                                $grid_default_value = 4
                            );

                            $attrs[0]['ip_box_quantity'] = "Inventory Product Box Qty";
                            $attrs[3]["ip_box_quantity"] = "number";
                            $attrs[0]['ip_item_quantity'] = "Inventory Product Item Qty";
                            $attrs[3]["ip_item_quantity"] = "number";
                            $attrs[0]['limit_quantity'] = "Inventory Product Limit Items Qty";
                            $attrs[3]["limit_quantity"] = "number";
                            echo generate_inputs_html_take_attrs($attrs);
                        ?>
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




