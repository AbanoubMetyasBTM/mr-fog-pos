
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

        <div class="section-wrapper mb-5">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Client Name</label>
                    <input value="{{$order->client_name}}" class="form-control" readonly>

                    <input hidden class="btm_select_2_selected_value_client_id" value="{{$order->client_id}}">
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
                    <label for="">Used Coupon Value</label>
                    <input  class="form-control" value="{{$order->used_coupon_value}}" readonly>
                </div >



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


        <form class="ajax_form add_client_order_class disable_enter_submit" id="save_form" method="POST" action="{{url("admin/clients-orders/return-order/$order->client_order_id")}}" enctype="multipart/form-data">

            <input hidden name="order_id" value="{{$order->client_order_id}}">
            <div class="mb-4">
                <h6 class="slim-pagetitle">Order Items</h6>
            </div>
            <div class="section-wrapper mb-5">

                <p class="mg-b-20 mg-sm-b-20"></p>

                <table class="table">
                    <tr style="font-size: 15px; font-weight: bold">
                        <th scope="col">Operation Type</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Item Type</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Item Cost</th>
                        <th scope="col">Total Cost</th>
                        <th scope="col">Promotion Id</th>
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
                                {{$item->operation_type}}
                                <input class="item_operation_type" hidden value="{{$item->operation_type}}">
                            </td>
                            <td>
                                {{$item->product_name}}
                                <?php if($item->operation_type == "buy" && $item->returned_qty < $item->order_quantity):?>
                                    <input hidden name="item_ids[]" value="{{$item->id}}">
                                <?php endif;?>
                            </td>
                            <td>
                                {{$item->item_type}}
                            </td>
                            <td>
                                {{$item->order_quantity}}
                                <input hidden class="order_quantity" value="{{$item->order_quantity}}">
                            </td>
                            <td>
                                {{$item->item_cost}}
                                <input hidden class="item_total_cost" value="{{$item->item_cost}}">
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


                            <td style="align-items: center">
                                {{$item->returned_qty}}
                                <input hidden class="returned_qty" value="{{$item->returned_qty}}">
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





            <div class="mb-4">
                <h6 class="slim-pagetitle">Payment Methods</h6>
            </div>
            <div class="section-wrapper mb-5" id="payment_methods_section">
                <p class="mg-b-20 mg-sm-b-20"></p>

                <div class="row mb-4">
                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Amount Will Return</label>
                        <input id="amount_will_return_id"
                               type="text"
                               name="returned_amount"
                               class="form-control"
                               readonly
                        >

                        <input hidden id="basic_returned_amount" value="0">
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Received Amount</label>
                        <input id="received_amount_id" type="number" name="received_amount" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Remain Of Return Amount</label>
                        <input id="remainder_of_amount_will_return"
                               type="text"
                               name="remainder_of_amount_will_return"
                               class="form-control"
                               readonly
                        >
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Client Wallet Amount</label>
                        <input value="{{$order->wallet_amount}}" class="form-control" readonly>
                    </div>



                </div>

                <table class="table client_order_return_items_payment_methods_table">
                    <!-- Paid Amount Inputs -->
                    <tr>
                        <td>
                            <label>Client Wallet Paid Amount</label>
                            <input class="form-control" value="<?php echo $order->wallet_paid_amount != null? $order->wallet_paid_amount : "0.00" ?>" readonly>
                        </td>
                        <td>
                            <label>Gift Card Paid Amount</label>
                            <input id="gift_card" class="form-control" value="<?php echo $order->gift_card_paid_amount != null? $order->gift_card_paid_amount : "0.00" ?>" readonly>
                        </td>
                        <td>
                            <label>Cash Paid Amount</label>
                            <input class="form-control" value="{{ $order->cash_paid_amount }}" readonly>
                        </td>
                        <td>
                            <label>Debit Card Paid Amount</label>
                            <input class="form-control" value="{{ $order->debit_card_paid_amount }}" readonly>
                        </td>
                        <td>
                            <label>Credit Card Paid Amount</label>
                            <input class="form-control" value="{{ $order->credit_card_paid_amount }}" readonly>
                        </td>
                        <td>
                            <label>Cheque Paid Amount</label>
                            <input class="form-control" value="{{ $order->cheque_paid_amount }}" readonly>
                        </td>
                    </tr>

                    <!-- Returned Amount Inputs -->
                    <tr>
                        <td>
                            <label>Client Wallet Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->wallet_return_amount !=null ? $order->wallet_return_amount : '0.00' ?>" readonly>
                        </td>
                        <td>
                            <label>Gift Card Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->gift_card_return_amount !=null ? $order->gift_card_return_amount : '0.00' ?>" readonly>
                        </td>
                        <td>
                            <label>Cash Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->cash_return_amount !=null ? $order->cash_return_amount : '0.00' ?>" readonly>
                        </td>
                        <td>
                            <label>Debit Card Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->debit_card_return_amount !=null ? $order->debit_card_return_amount : '0.00' ?>" readonly>
                        </td>
                        <td>
                            <label>Credit Card Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->credit_card_return_amount !=null ? $order->credit_card_return_amount : '0.00' ?>" readonly>
                        </td>
                        <td>
                            <label>Cheque Returned Amount</label>
                            <input class="form-control" value="<?php echo $order->cheque_return_amount !=null ? $order->cheque_return_amount : '0.00' ?>" readonly>
                        </td>
                    </tr>

                    <!-- Amount Will Return Inputs -->
                    <tr>
                        <td>
                            <label>Client Wallet Amount Will Return</label>
                            <input type="number" class="form-control amount_will_return" name="wallet_amount_will_return" value="" step="0.01">
                        </td>
                        <td>
                            <label>Gift Card Amount Will Return</label>
                            <?php if ($order->gift_card_paid_amount != null || $order->gift_card_paid_amount != 0): ?>
                                <input type="number" class="form-control amount_will_return" name="gift_card_amount_will_return" value="" max="{{$order->gift_card_paid_amount}}" step="0.01">
                            <?php else:?>
                                <input class="form-control amount_will_return" name="gift_card_amount_will_return" value="0.00" readonly step="0.01">
                            <?php endif;?>

                        </td>
                        <td>
                            <label>Cash Amount Will Return</label>
                            <input type="number" class="form-control amount_will_return" name="cash_amount_will_return" value="" step="0.01">
                        </td>
                        <td>
                            <label>Debit Card Amount Will Return</label>
                            <input type="number" class="form-control amount_will_return" name="debit_card_amount_will_return" value="" step="0.01">
                        </td>
                        <td>
                            <label>Credit Card Amount Will Return</label>
                            <input type="number" class="form-control amount_will_return" name="credit_card_amount_will_return" value="" step="0.01">
                        </td>
                        <td>
                            <label>Cheque Amount Will Return</label>
                            <input type="number" class="form-control amount_will_return" name="cheque_amount_will_return" value="" step="0.01">
                        </td>
                    </tr>

                    <?php if (false): ?>
                        <tr>
                            <td></td>
                            <td>
                                <label>Debit Card Receipt Image</label>
                                <input id="formFile" type="file" class="form-control" name="debit_card_receipt_img">
                            </td>
                            <td>
                                <label>Credit Card Receipt Image</label>
                                <input type="file" class="form-control" name="credit_card_receipt_img">
                            </td>
                            <td>
                                <label>Cheque Receipt Image</label>
                                <input type="file" class="form-control" name="cheque_card_receipt_img">
                            </td>
                        </tr>
                    <?php endif; ?>

                </table>

            </div>


            <div class="mb-4">
                <h6 class="slim-pagetitle">Order Cost</h6>
            </div>
            <div class="section-wrapper">

                <p class="mg-b-20 mg-sm-b-20"></p>
                <div class="row mb-4">

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Total Items Cost</label>
                        <input value="{{$order->total_items_cost}}" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Order Cost After Discount</label>
                        <input value="{{$order->total_cost}}" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Paid Amount</label>
                        <input value="{{ $order->total_paid_amount }}" id="paid_amount" name="paid_amount" class="form-control" max="" readonly>
                    </div>

                    <div class="col-md-3 label_of_data_supplier_order">
                        <label>Total Return Amount</label>
                        <input value="{{$order->total_return_amount}}" class="form-control" readonly>
                    </div>

                </div>

                {{csrf_field()}}



                <div class="row mb-2">







                </div>

                <div class="row mb-0">
                    <div class="col-md-3" >
                        <input id="submit" type="submit" value="Save" class="btn btn-primary bd-0" style="border-radius: 5px">
                    </div>
                </div>

            </div>
        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->

