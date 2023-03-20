<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Last Orders</h6>
</div>
<div class="card card-table">
    <div class="card-header">
        <h6 class="slim-card-title">orders History</h6>
    </div><!-- card-header -->
    <div class="table-responsive">
        <table class="table mg-b-0 tx-13">
            <thead>
                <tr class="tx-10">
                    <th class="wd-10p pd-y-5">&nbsp;</th>
                    <th class="pd-y-5">Order Id</th>
                    <th class="pd-y-5">Branch Name</th>
                    <th class="pd-y-5">Total Cost</th>
                    <th class="pd-y-5">Total Paid Amount</th>
                    <th class="pd-y-5">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($thisDateLastTenOrders as $order)
                    <tr>
                        <th class="wd-10p pd-y-5">&nbsp;</th>
                        <td>
                            <a href="{{url("/admin/clients-orders?order_id=$order->client_order_id")}}">
                                {{$order->client_order_id}}
                            </a>
                        </td>
                        <td>
                            <a href="" class="tx-inverse tx-14 tx-medium d-block">{{$order->branch_name}}</a>
                            <span class="tx-11 d-block">{{$order->branch_currency}}</span>
                        </td>
                        <td class="tx-12">
                            <span class="square-8 bg-success mg-r-5 rounded-circle"></span> {{$order->total_cost}}
                        </td>
                        <td>{{$order->total_paid_amount}}</td>
                        <td>{{dump_date($order->created_at)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
