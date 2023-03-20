<link rel="stylesheet" href="{{url("/")}}/public/admin/css/print_order.css">

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <button class="hide_at_print btn btn-primary" onclick="print();">
                Print
            </button>

            <div id="invoice-POS">

                <div id="top" style="text-align: center;">
                    <div>
                        <img  src="{{get_image_from_json_obj($order->branch_img_obj)}}" id="branch_logo">
                        <br>
                        <?php
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                        ?>
                        <img src="data:image/png;base64,{{base64_encode($generator->getBarcode($order->client_order_id, $generator::TYPE_CODE_128))}}" alt="">

                    </div>
                    <div class="info">
                        <h1>Order Id #{{$order->client_order_id}}</h1>
                        <h2>{{$order->branch_name}}</h2>
                        <h3>welcome text !</h3>
                    </div>
                </div>

                <div id="mid">
                    <div class="info mt-1">
                        <p>
                            <span style="font-weight: bold"> Client Name : </span>    {{ $order->client_name }}
                            <br>
                            <span style="font-weight: bold"> Added By : </span>       {{ $order->emp_name }}
                            <br>
                            <span style="font-weight: bold"> Register Name : </span>  {{ $order->register_name }}
                            <br>
                            <span style="font-weight: bold"> Order Type : </span>     {{ $order->order_type }}
                            <br>
                            <span style="font-weight: bold"> Created At : </span>     {{ $order->created_at }}
                            <br>
                        </p>
                    </div>
                </div><!--End Invoice Mid-->

                <div id="bot">

                    <div id="table">
                        <table class="mb1">
                            <tr class="tabletitle">
                                <td class="item wd-30p-force"><h2>Item</h2></td>
                                <td class="item wd-10p-force"><h2>Type</h2></td>
                                <td class="item wd-10p-force"><h2>Operation</h2></td>
                                <td class="Rate wd-10p-force"><h2>Cost</h2></td>
                                <td class="Rate wd-10p-force"><h2>Qty</h2></td>
                                <td class="Rate wd-10p-force"><h2>Total Cost</h2></td>
                            </tr>

                            <?php foreach ($items as $item): ?>
                                <tr class="service">
                                    <td class="tableitem"><p class="itemtext">{{$item->product_name}}</p></td>
                                    <td class="Rate"><p class="itemtext">{{$item->item_type}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$item->operation_type}}</p></td>
                                    <td class="payment"><p class="itemtext">{{$item->item_cost}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$item->order_quantity}}</p></td>
                                    <td class="payment"><p class="itemtext">{{$item->total_items_cost}}</p></td>
                                </tr>
                            <?php endforeach;?>
                        </table>

                        <br>
                        <table id="table_order_cost">
                            <tr class="tableOrderCostTitle">
                                <td class="Rate"><h2>Total Items Cost</h2></td>
                                <td class="payment"><h2>{{$order->total_items_cost}} {{$order->branch_currency}}</h2></td>
                            </tr>
                            <tr class="tableOrderCostTitle">
                                <td class="Rate"><h2>Order Cost After Discount</h2></td>
                                <td class="payment"><h2>{{$order->total_cost}} {{$order->branch_currency}}</h2></td>
                            </tr>
                            <tr class="tableOrderCostTitle">
                                <td class="Rate"><h2>Paid Amount</h2></td>
                                <td class="payment"><h2>{{$order->total_paid_amount}} {{$order->branch_currency}}</h2></td>
                            </tr>

                            @if($order->total_return_amount > 0 )
                                <tr class="tableOrderCostTitle">
                                    <td class="Rate"><h2>Total Return Amount</h2></td>
                                    <td class="payment"><h2>{{$order->total_return_amount}} {{$order->branch_currency}}</h2></td>
                                </tr>
                            @endif

                        </table>

                    </div><!--End Table-->

                    <div id="legalcopy">
                        <p class="legal">
                            <?php
                                $branchTaxes = json_decode($order->total_taxes, true);
                            ?>

                            <?php foreach ($branchTaxes as $tax):?>

                                We applied a {{ $tax['tax_percent'] }} % {{ strtolower($tax['tax_label']) }}.
                                <br>

                            <?php endforeach ?>

                        </p>
                    </div>

                </div><!--End InvoiceBot-->
            </div><!--End Invoice-->

        </div>
    </div>
</div>
