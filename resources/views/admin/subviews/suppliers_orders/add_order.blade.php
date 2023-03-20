<?php

    /**
     * @var $item_data object
    */

    $header_text        = "Add New";


?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/suppliers-orders")}}">Suppliers Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <form id="save_form" method="POST" action="{{url("admin/suppliers-orders/add-order")}}" class="ajax_form disable_enter_submit add_supplier_order_class"  enctype="multipart/form-data">

            <div class="section-wrapper mg-y-10">
                <label class="section-title">Order Data</label>
                <p class="mg-b-20 mg-sm-b-20"></p>
                <div class="row ">
                    <?php

                        echo generate_select_tags_v2([
                            "field_name"              => "supplier_id",
                            "label_name"              => "Supplier Name",
                            "text"                    => $suppliers->pluck("sup_name")->all(),
                            "values"                  => $suppliers->pluck("sup_id")->all(),
                            "class"                   => "form-control select2_search",
                            "grid"                    => "col-md-4",
                        ]);

                        echo generate_select_tags_v2([
                            "field_name"              => "branch_id",
                            "label_name"              => "Branch Name",
                            "text"                    => array_merge(["None"], $branches->pluck("branch_name")->all()),
                            "values"                  => array_merge([""], $branches->pluck("branch_id")->all()),
                            "class"                   => "form-control select2_search",
                            "grid"                    => "col-md-4",
                        ]);


                        echo generate_select_tags_v2([
                            "field_name"              => "basic_inv_id",
                            "label_name"              => "Inventory Name",
                            "text"                    => $inventories->pluck("inv_name")->all(),
                            "values"                  => $inventories->pluck("inv_id")->all(),
                            "class"                   => "form-control select2_search",
                            "grid"                    => "col-md-4",
                        ]);

                        $normal_fields = [
                            "ordered_at"
                        ];

                        $attrs = generate_default_array_inputs_html(
                            $normal_fields,
                            $data               = "",
                            $key_in_all_fields  = "yes",
                            $requried           = "required",
                            $grid_default_value = 3
                        );

                        $attrs[0]['ordered_at']        = "Ordered At";
                        $attrs[3]["ordered_at"]        = "date";

                        echo generate_inputs_html_take_attrs($attrs);

                        echo generate_select_tags_v2([
                            "field_name"              => "order_status",
                            "label_name"              => "Order Status",
                            "text"                    => ["done", "pending"],
                            "values"                  => [ "done", "pending"],
                            "class"                   => "form-control select2_primary",
                            "grid"                    => "col-md-3",
                        ]);

                        $normal_fields = [
                            "original_order_id", "order_img_obj"
                        ];

                        $attrs = generate_default_array_inputs_html(
                            $normal_fields,
                            $data               = "",
                            $key_in_all_fields  = "yes",
                            $requried           = "",
                            $grid_default_value = 3
                        );

                        $attrs[0]['original_order_id']      = "Invoice Id";
                        $attrs[3]["original_order_id"]      = "text";
                        $attrs[0]['order_img_obj']          = "Upload Supplier Invoice Image";
                        $attrs[3]["order_img_obj"]          = "file";

                        echo generate_inputs_html_take_attrs($attrs);
                    ?>
                </div>

            </div>

            <div class="row order_items_row">
                <div class="col-md-12">
                    <div class="section-wrapper mg-y-20 order_items_section">
                        <label class="section-title">Order Items</label>
                        <p class="mg-b-20 mg-sm-b-20"></p>


                        <div class="">
                            <div class="form-group">
                                <button type="button" class="btn btn-info add_new_order_item">
                                    Add New Item <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <table class="table">
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Inventory Name</th>
                                <th scope="col">Item Type</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Item Cost</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Action</th>

                            </tr>
                            <tr class="item_row">
                                <td>
                                    <?php
                                        generateBTMSelect2([
                                            "field_name"            => "product_sku[]",
                                            "label_name"            => "Product name",
                                            "data-placeholder"      => "Search by product name or barcode",
                                            "data-url"              => url("admin/products-sku/get-product-sku-by-barcode"),
                                            "class"                  => "product_sku form-control convert_select_to_btm_select2",
                                            "hide_label"            => true,
                                            "data-run_after_select" => "supplierOrderAfterSelectProduct",
                                            "required"              => "required"
                                        ]);
                                    ?>
                                </td>
                                <td>

                                    <?php
                                        echo generate_select_tags_v2([
                                            "field_name" => "inv_id[]",
                                            "label_name" => "Inventory Name",
                                            "text"       => $inventories->pluck("inv_name")->all(),
                                            "values"     => $inventories->pluck("inv_id")->all(),
                                            "class"      => "form-control inv_id",
                                            "hide_label" => true,
                                        ]);
                                    ?>
                                </td>
                                <td>

                                    <?php
                                        echo generate_select_tags_v2([
                                            "field_name" => "item_type[]",
                                            "label_name" => "Item Type",
                                            "text"       => ["Item", "Box"],
                                            "values"     => ["item", "box"],
                                            "class"      => "form-control item_type",
                                            "hide_label" => true,
                                        ]);
                                    ?>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number"  name="order_quantity[]" min="1" class="form-control order_quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="item_total_cost[]" class="form-control item_total_cost">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="total_items_cost[]" class="form-control items_cost" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <a href="#" class="btn btn-danger remove_order_item_row" style="border-radius: 5px">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            @include('admin.subviews.suppliers_orders.add_order_components.order_cost')

            {{csrf_field()}}


            <div class="form-layout-footer mb-3">
                <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
            </div>

        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->





