
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/suppliers-orders")}}">Supplier Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Show Order #{{$order->supplier_order_id}}</li>
            </ol>
            <h6 class="slim-pagetitle">Show Order #{{$order->supplier_order_id}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-5">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">

                <div class="col-md-3" >
                    <label>Supplier Name</label>
                    <input value="{{$order->sup_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3">
                    <label>Total Amount Of {{$order->sup_name}} </label>
                    <input  class="form-control" value="{{$wallet_obj_of_supplier->wallet_amount}}" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Branch Name</label>
                    <input value="<?php echo !is_null($order->branch_name)? $order->branch_name : "None"?>" class="form-control" readonly>
                </div>
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Ordered At</label>
                    <input value="<?php echo date("Y-m-d", strtotime($order->ordered_at)) ?>" class="form-control" readonly>
                </div>

            </div>
            <div class="row mb-2">
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Status</label>
                    <input value="{{capitalize_string($order->order_status)}}" class="form-control" readonly>
                </div>
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Invoice Id</label>
                    <input value="{{$order->original_order_id}}" class="form-control" readonly>
                </div>
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Added By</label>
                    <input  class="form-control" value="{{$order->emp_name}}" readonly>
                </div>


                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Invoice Image</label>
                    <?php if((!empty($order->original_order_img_obj))): ?>
                        <a href='{{get_image_from_json_obj($order->original_order_img_obj)}}' class='btn btn-primary form-control'
                           target='_blank' style='border-radius: 5px'>
                            Show Image
                        </a>
                    <?php else:?>
                        <a href='#' class='btn btn-primary form-control'
                            style='border-radius: 5px'>
                            No Found Image
                        </a>
                    <?php endif; ?>
                </div>


                <div class="col-md-3 label_of_data_supplier_order">
                    <?php if($order->order_status == 'pending' && havePermission("admin/suppliers_orders", "show_order")): ?>
                        <label>Actions</label>


                        <div style="display: flex">

                            <a class="btn btn-primary form-control mr-3"
                               href="{{url("admin/suppliers-orders/make-order-done/$order->supplier_order_id")}}">
                                make order done
                            </a>

                            <form method="POST" action="{{url("admin/suppliers-orders/cancel-order/$order->supplier_order_id")}}" enctype="multipart/form-data">
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


        <div class="mb-4">
            <h6 class="slim-pagetitle">Order Cost</h6>
        </div>
        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">

                <div class="col-md-6 label_of_data_supplier_order">
                    <label>Additional Fess</label>
                    <input value="{{$order->additional_fees}}" class="form-control" readonly>
                </div>

                <div class="col-md-6 label_of_data_supplier_order">
                    <label>Additional Fess Description</label>
                    <textarea  class="form-control" rows="2" readonly>{{$order->additional_fees_desc}}</textarea>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Items Cost + Additional Fees</label>
                    <input value="{{$order->total_cost}}" class="form-control" readonly>
                </div>

                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Items Order Cost</label>
                    <input value="{{$order->total_items_cost}}" class="form-control" readonly>
                </div>

                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Paid Amount</label>
                    <input value="{{$order->paid_amount}}" class="form-control" readonly>
                </div>


            </div>
        </div>


        <form id="save_form" class="supplier_return_order_class ajax_form" method="POST" action="{{url("admin/suppliers-orders/return-items/$order->supplier_order_id")}}" enctype="multipart/form-data">

            <div class="mb-4">
                <h6 class="slim-pagetitle">Order Items</h6>
            </div>
            <div class="section-wrapper mb-5">

                <p class="mg-b-20 mg-sm-b-20"></p>

                <table class="table">
                    <tr style="font-size: 15px; font-weight: bold">
                        <th scope="col">Operation Type</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Inventory Name</th>
                        <th scope="col">Item Type</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Item Cost</th>
                        <th scope="col">Total Cost</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Returned Qty</th>

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

                    <?php  foreach ($items as $item):?>

                        <tr id="{{$item->id}}" class="item_will_return <?php echo $item->operation_type == "buy"? "table-success" :"table-warning" ?>">
                            <td>
                                <span>
                                    {{$item->operation_type}}
                                    <input class="item_operation_type" hidden value="{{$item->operation_type}}">
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->product_name}}
                                    <?php if($item->operation_type == "buy" && $item->returned_qty < $item->order_quantity):?>
                                        <input hidden name="item_ids[]" value="{{$item->id}}">
                                    <?php endif;?>
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->inv_name}}
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->item_type}}
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->order_quantity}}
                                    <input hidden class="order_quantity" value="{{$item->order_quantity}}">
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->item_total_cost}}
                                </span>
                                <input hidden class="item_total_cost" value="{{$item->item_total_cost}}">
                            </td>
                            <td>
                                <span>
                                    {{$item->total_items_cost}}
                                </span>
                            </td>
                            <td>
                                <span>
                                    {{$item->created_at}}
                                </span>
                            </td>


                            <td style="align-items: center">
                                <span>
                                    {{$item->returned_qty}}
                                </span>
                                <input hidden  class="returned_qty" value="{{$item->returned_qty}}">

                            </td>

                            <td>
                                <?php if ($order->order_status == "done" && $item->operation_type == "buy" && $item->returned_qty < $item->order_quantity):?>
                                    <input type="number"
                                           name="want_return_qty[]"
                                           class="form-control items_cost mr-2 want_return_qty"
                                           data-item_total_cost_value="{{$item->item_total_cost}}"
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

            <?php if($order->order_status == "done"): ?>

                <div class="mb-4">
                    <h6 class="slim-pagetitle">Return Order</h6>
                </div>

                <div class="section-wrapper">

                    <p class="mg-b-20 mg-sm-b-20"></p>

                    <div class="row mb-2">
                        <div class="col-md-4 label_of_data_supplier_order">
                            <label>Total Return Amount</label>
                            <input value="{{$order->total_return_amount}}" class="form-control" readonly>
                        </div>

                        <div class="col-md-4 label_of_data_supplier_order">
                            <label>Items Will Return Cost</label>
                            <input id="returned_amount_id"
                                   type="text"
                                   name="returned_amount"
                                   class="form-control"
                                   readonly>

                            <input hidden id="basic_returned_amount" value="0">
                        </div>

                        <div class="col-md-4 label_of_data_supplier_order">
                            <label>Received Cash Amount</label>
                            <input id="received_amount_id" type="number" name="received_amount" class="form-control">
                        </div>
                    </div>

                    {{csrf_field()}}

                    <div class="row mb-0">
                        <div class="col-md-3" >
                            <input id="submit" type="submit" value="Save" class="btn btn-primary bd-0" style="border-radius: 5px">
                        </div>
                    </div>

                </div>
            <?php else:   ?>
            <div class="mb-4">
                <h6 class="slim-pagetitle">Order is peinding</h6>
            </div>
            <?php endif; ?>

        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->

