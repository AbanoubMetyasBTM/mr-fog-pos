<input type="hidden" class="this_is_make_order_done_page">
<input type="hidden" class="client_obj" value="{{json_encode($clientObj)}}">


<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/clients-orders")}}">Clients Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Show Order #{{$order->client_order_id}}</li>
            </ol>
            <h6 class="slim-pagetitle">Show Order #{{$order->client_order_id}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Client Name</label>
                    <input value="{{$order->client_name}}" class="form-control" readonly>
                    <input hidden  name="client_id" class="btm_select_2_selected_value_client_id" value="{{$order->client_id}}">
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Added By</label>
                    <input  class="form-control" value="{{$order->emp_name}}" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Branch Name</label>
                    <input value="{{$order->branch_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Status</label>
                    <input value="{{capitalize_string($order->order_status)}}" class="form-control" readonly>
                </div>
            </div>
            <div class="row mb-2">

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Register Name</label>
                    <input  class="form-control" value="{{$order->register_name}}" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Order Type</label>
                    <input class="form-control" value="{{capitalize_string($order->order_type)}}" readonly>
                </div>



                <div class="col-md-3 label_of_data_supplier_order">
                    <?php if($order->order_status == 'pick_up' && havePermission("admin/clients_orders", "show_action")): ?>
                        <label>Actions</label>


                        <div style="display: flex">
                            <form method="POST" action="{{url("admin/clients-orders/cancel-order/$order->client_order_id")}}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <button type="submit" class="btn btn-danger form-control ask_before_go">
                                    Cancel Order
                                </button>
                            </form>
                        </div>


                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php

            if($order->order_status == 'pick_up'){
                $url = url("admin/clients-orders/make-order-done/$order->client_order_id");
            }
            else{
                $url = url("admin/clients-orders/return-items/$order->client_order_id");
            }

        ?>

        <form id="save_form" method="POST" action="{{$url}}" enctype="multipart/form-data" class="ajax_form disable_enter_submit">

            <input hidden name="order_id" value="{{$order->client_order_id}}">
            <input hidden name="client_id" value="{{$order->client_id}}">

            <div class="row">
                <div class="col-md-12">
                    <div class="section-wrapper mg-y-5">
                        <label class="section-title">Order Items</label>
                        <p class="mg-b-20 mg-sm-b-20"></p>

                        <table class="table">
                            <tr style="font-size: 15px; font-weight: bold">
                                <th scope="col">Operation Type</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Item Type</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Item Cost</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Promotion Id</th>
                                <th scope="col">Created At</th>

                                <?php if($order->order_status == "done"):?>
                                <th scope="col">Want Return Qty</th>
                                <?php endif;?>

                                <th scope="col">
                                    <?php if($order->order_status == "done"):?>
                                    <div class="form-group">
                                        <button id="return_all_items" type="button" class="btn btn-primary" style="border-radius: 5px">
                                            Return All Items
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </th>
                            </tr>

                            <?php foreach ($items as $item):?>

                            <tr id="{{$item->id}}" class="item_will_return <?php echo $item->operation_type == "buy"? "table-success" :"table-warning" ?>">
                                <td>
                                    {{$item->operation_type}}
                                </td>
                                <td>
                                    {{$item->product_name}}
                                </td>
                                <td>
                                    {{$item->item_type}}
                                </td>
                                <td>
                                    {{$item->order_quantity}}
                                </td>

                                <td>
                                    {{$item->item_cost}}
                                </td>
                                <td>
                                    {{$item->total_items_cost}}
                                    <input hidden class="form-control items_cost" value="{{$item->total_items_cost}}" readonly>
                                </td>
                                <td>
                                    <?php if ($item->promotion_id != null): ?>
                                    {{$item->promotion_id}}
                                    <?php else:?>
                                    -------
                                    <?php endif; ?>
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>

                                <td>
                                    <?php if ($order->order_status == "done" && $item->operation_type == "buy" && $item->returned_qty < $item->order_quantity):?>
                                    <input type="number"
                                           name="want_return_qty[]"
                                           class="form-control items_cost mr-2 want_return_qty"
                                           style="width: 140px; height: 35px; border-radius: 5px"
                                           max="{{$item->order_quantity - $item->returned_qty}}"
                                           min="1"
                                    >
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($order->order_status == "done" && $item->operation_type == "buy" && $item->returned_qty < $item->order_quantity):?>

                                    <input type="button"
                                           value="Return All Qty"
                                           style="padding: 6px; border-radius: 5px; font-size: 14px"
                                           class="btn btn-primary bd-0 return_all_item_qty"
                                    >
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>
            </div>



            @include("admin.subviews.clients_orders.add_order_components.loyalty_points")
            @include("admin.subviews.clients_orders.add_order_components.discounts")
            @include("admin.subviews.clients_orders.add_order_components.payment_methods")


            <div class="row">
                <div class="col-md-12">
                    <div class="section-wrapper mg-y-5">
                        <label class="section-title">Order Cost</label>
                        <p class="mg-b-20 mg-sm-b-20"></p>

                        <div class="row mb-4">

                            <div class="col-md-4 label_of_data_supplier_order">
                                <label>Total Items Cost</label>
                                <input value="{{$order->total_items_cost}}" class="form-control " readonly>
                            </div>

                            <div class="col-md-4 label_of_data_supplier_order">
                                <label>Order Cost</label>
                                <input value="{{$order->total_cost}}" class="form-control total_cost" readonly>
                            </div>

                            <div class="col-md-4 label_of_data_supplier_order">
                                <label>Paid Amount</label>
                                <input value="{{ $order->total_paid_amount }}" id="paid_amount" name="paid_amount" class="form-control" max="" readonly>
                            </div>

                        </div>

                        <div id="coupon_notes_div" class="row"></div>

                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-md-2">
                                <input id="submit" type="submit" value="Make Order Done" class="btn btn-primary form-control">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->

