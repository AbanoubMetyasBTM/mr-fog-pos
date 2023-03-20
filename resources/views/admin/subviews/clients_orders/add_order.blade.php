<?php

    /**
     * @var $item_data object
     * @var $all_points_redeems \Illuminate\Support\Collection
    */

    $header_text = "Add New";
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/clients-orders")}}">Clients Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->


        <form id="save_form disable_enter_submit" method="POST" action="{{url("admin/clients-orders/add-order")}}" class="ajax_form add_client_order_class"  enctype="multipart/form-data">

            <div class="section-wrapper mg-y-5">
                <label class="section-title">Order Data</label>
                <p class="mg-b-20 mg-sm-b-40"></p>
                <div class="row">
                    <?php
                        generateBTMSelect2([
                            "field_name"            => "client_id",
                            "label_name"            => "Select Client",
                            "data-placeholder"      => "Search by client name or phone",
                            "data-url"              => langUrl("admin/clients-orders/get-client-by-name-or-phone"),
                            "class"                  => "client_id form-control convert_select_to_btm_select2",
                            "hide_label"            => false,
                            "grid"                  => "col-md-4 select_client_parent_div",
                            "required"              => "required",
                            "data-run_after_select" => "runAfterSelectClientId"
                        ]);
                    ?>

                    <div class="col-md-4 hide_div show_client_parent_div">
                        <label for="">Selected Client</label>
                        <div class="alert alert-info">
                            <span class="show_client_name"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="mb-4" style="display: block">Order Status</label>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label mr-xl-5">
                                <input  type="radio" name="order_status" id="status_done" class="form-check-input order_status" value="done" style="transform: scale(1.6)" checked>
                                Done
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="order_status" id="status_pick_up" class="form-check-input order_status" value="pick_up" style="transform: scale(1.6)" >
                                Pick Up
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 hide_until_change_order" id="pick_up_date">
                        <div class="form-group">
                            <label>Pick Up Date</label>
                            <input type="date" name="pick_up_date"  class="form-control">
                        </div>
                    </div>


                </div>

            </div>

            <div class="hide_until_select_client row order_items_row">
                <div class="col-md-12">
                    <div class="section-wrapper mg-y-5 order_items_section">
                        <label class="section-title">Order Items</label>
                        <p class="mg-b-20 mg-sm-b-20"></p>

                        <button type="button" class="btn btn-info add_new_client_order_item mb-2">
                            Add New Item <i class="fa fa-plus"></i>
                        </button>


                        <table id="client_order_items" class="table">
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Item Type</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Item Cost</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Action</th>

                            </tr>
                            <tr class="client_order_items">
                                <td>
                                    <?php
                                        generateBTMSelect2([
                                            "field_name"                           => "product_sku[]",
                                            "label_name"                           => "Product name",
                                            "data-placeholder"                     => "Search by product name or barcode",
                                            "data-url"                             => langUrl("admin/clients-orders/get-product-sku-by-name-barcode-client"),
                                            "class"                                => "product_sku form-control convert_select_to_btm_select2",
                                            "hide_label"                           => true,
                                            "data-run_after_select"                => "clientOrderAfterSelectProduct",
                                            "required"                             => "required",
                                            "data-append_data_from_dom_by_classes" => json_encode([
                                                "selected_client_id" => ".btm_select_2_selected_value_client_id"
                                            ])
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
                                        <input type="number"  name="order_quantity[]" min="1" class="form-control order_quantity" value="1" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="item_total_cost[]" class="form-control item_total_cost" readonly>
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

                        <div class="col-md-12">
                            <?php
                                $current_branch_data->branch_taxes = json_decode($current_branch_data->branch_taxes);
                                if(!is_array($current_branch_data->branch_taxes)){
                                    $current_branch_data->branch_taxes = [];
                                }
                            ?>

                            <?php if(count($current_branch_data->branch_taxes)): ?>
                                <div class="alert alert-info branch_taxes_div">
                                    <?php foreach($current_branch_data->branch_taxes as $key=>$item): ?>
                                        <label for="">Taxes Applied: {{$item->tax_label}} : {{$item->tax_percent}}%</label>
                                        <br>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-info client_taxes_div">
                            </div>


                        </div>


                        <div id="notes">

                        </div>

                    </div>
                </div>
            </div>

            @include("admin.subviews.clients_orders.add_order_components.loyalty_points")
            @include("admin.subviews.clients_orders.add_order_components.discounts")
            @include("admin.subviews.clients_orders.add_order_components.payment_methods")

            <div class="hide_until_select_client row">
                <div class="col-md-12">
                    <div class="section-wrapper mg-y-5">
                        <label class="section-title">Order Cost</label>
                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Total Cost</label>
                                    <input type="text" name="total_items_cost" class="form-control total_items_cost" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Discount</label>
                                    <input type="text" id="coupon_notes_div" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Total Cost After Discount</label>
                                    <input type="text" name="total_cost" class="form-control total_cost" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Paid Amount</label>
                                    <input type="number" id="paid_amount" name="paid_amount" class="form-control" max="" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{csrf_field()}}


            <div class="hide_until_select_client form-layout-footer mb-3">
                <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
            </div>

        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->





