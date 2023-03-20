<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/suppliers-orders")}}">Supplier Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Show Order #{{$order->supplier_order_id}}</li>
            </ol>




            <h6 class="slim-pagetitle">Make Order Done #{{$order->supplier_order_id}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-5">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">

                <div class="col-md-3" >
                    <label>Supplier Name</label>
                    <input value="{{$order->sup_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Added By</label>
                    <input  class="form-control" value="{{$order->emp_name}}" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Branch Name</label>
                    <input value="<?php echo !is_null($order->branch_name)? $order->branch_name : "None"?>" class="form-control" readonly>
                </div>
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Ordered At</label>
                    <input value="<?php echo date("Y-m-d", strtotime($order->ordered_at)) ?>" class="form-control" readonly>
                </div>
                <input type="text" hidden id="total_items_cost_value" value="{{$order->total_items_cost}}">



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

        <form id="save_form" method="POST" action="{{url("admin/suppliers-orders/make-order-done/$order->supplier_order_id")}}" class="supplier_make_order_done_class" enctype="multipart/form-data">

            {{csrf_field()}}

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

                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            @include('admin.subviews.suppliers_orders.add_order_components.order_cost')
            <div class="row mb-0">
                <div class="col-9 col-md-9"></div>
                <div class="col-md-3" >
                    <button type="submit" class="btn btn-primary form-control ask_before_go">
                        Make Order Done
                    </button>
                </div>
            </div>



        </form>



    </div><!-- container -->
</div><!-- slim-mainpanel -->

