
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
                    <label>Total Amount Of {{$order->client_name}}</label>
                    <input value="{{$clientObj['wallet_amount']}}" class="form-control" readonly>
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
                    <label>Added By</label>
                    <input  class="form-control" value="{{$order->emp_name}}" readonly>
                </div>

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
                    <th scope="col">Quantity</th>
                    <th scope="col">Item Cost</th>
                    <th scope="col">Total Cost</th>
                    <th scope="col">Promotion Id</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Returned Qty</th>
                </tr>

                <?php  foreach ($items as $item):?>

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
                            {{$item->returned_qty}}
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php
                $total_taxes = json_decode($order->total_taxes);
                if(!is_array($total_taxes)){
                    $total_taxes = [];
                }
            ?>

            <?php if(count($total_taxes)): ?>
                <div class="alert alert-info branch_taxes_div">
                    <?php foreach($total_taxes as $key=>$item): ?>
                        <label for="">Taxes Applied: {{$item->tax_label}} : {{$item->tax_percent}}%</label>
                        <br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>



        <div class="mb-4">
            <h6 class="slim-pagetitle">Discounts</h6>
        </div>
        <div class="section-wrapper mb-5" id="payment_methods_section">
            <p class="mg-b-20 mg-sm-b-20"></p>
            <table class="table">
                <tr>
                    <td>
                        <label>Client Wallet Paid Amount</label>
                        <input value="<?php echo $order->wallet_paid_amount != null ? $order->wallet_paid_amount : 0?>" class="form-control"  readonly>
                    </td>
                    <td>
                        <label>Gift Card Number</label>
                        <input value="{{$order->card_unique_number}}" class="form-control" readonly>
                    </td>
                    <td>
                        <label>Gift Card Paid Amount</label>
                        <input value="<?php echo $order->gift_card_paid_amount != null ? $order->gift_card_paid_amount : 0?>" class="form-control" readonly>
                    </td>

                </tr>
                <tr>
                    <td>
                        <label for="">Coupon Code value</label>
                        <input value="{{$order->used_coupon_value}}" class="form-control" readonly>
                    </td>
                    <td>
                        <label>Client Wallet Returned Amount</label>
                        <input value="<?php echo $order->wallet_return_amount != null ? $order->wallet_return_amount : 0?>" class="form-control"  readonly>
                    </td>
                    <td>
                        <label>Gift Card Returned Amount</label>
                        <input value="<?php echo $order->gift_card_return_amount != null ? $order->gift_card_return_amount : 0?>" class="form-control" readonly>
                    </td>
                </tr>
            </table>

        </div>


        <div class="mb-4">
            <h6 class="slim-pagetitle">Payment Methods</h6>
        </div>
        <div class="section-wrapper mb-5" id="payment_methods_section">
            <p class="mg-b-20 mg-sm-b-20"></p>
            <table class="table">
                <tr>
                    <td style="width: 25% !important;">
                        <label>Cash Paid Amount</label>
                        <input class="form-control payment_method" value="{{$order->cash_paid_amount}}" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Debit Card Paid Amount</label>
                        <input class="form-control payment_method" value="{{$order->debit_card_paid_amount}}" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Credit Card Paid Amount</label>
                        <input class="form-control payment_method" value="{{$order->credit_card_paid_amount}}" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Cheque Paid Amount</label>
                        <input class="form-control payment_method" value="{{$order->cheque_paid_amount}}" readonly>
                    </td>
                </tr>

                <?php if(false):?>
                    <tr>
                        <td></td>
                        <td>
                            <label>Debit Card Receipt Image</label>
                            <?php if((!empty($order->debit_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($order->debit_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label>Credit Card Receipt Image</label>
                            <?php if((!empty($order->credit_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($order->credit_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label>Cheque Receipt Image</label>
                            <?php if((!empty($order->cheque_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($order->cheque_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td style="width: 25% !important;">
                        <label>Cash Returned Amount</label>
                        <input class="form-control" value="<?php echo $order->cash_return_amount != null ? $order->cash_return_amount : 0?>" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Debit Card Returned Amount</label>
                        <input class="form-control" value="<?php echo $order->debit_card_return_amount != null ? $order->debit_card_return_amount : 0?>" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Credit Card Returned Amount</label>
                        <input class="form-control" value="<?php echo $order->credit_card_return_amount != null ? $order->credit_card_return_amount : 0?>" readonly>
                    </td>
                    <td style="width: 25% !important;">
                        <label>Cheque Returned Amount</label>
                        <input class="form-control" value="<?php echo $order->cheque_return_amount != null ? $order->cheque_return_amount : 0?>" readonly>
                    </td>
                </tr>


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
                    <input value="{{$order->total_paid_amount}}"  class="form-control" max="" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Total Return Amount</label>
                    <input value="{{$order->total_return_amount}}" class="form-control" readonly>
                </div>
            </div>
        </div>


    </div><!-- container -->
</div><!-- slim-mainpanel -->

