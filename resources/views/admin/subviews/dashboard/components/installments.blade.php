<div class="row">
    <div class="col-lg-6 mg-t-20 mg-lg-t-0">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Debt installments</h6>
        </div>
        <div class="card card-table">
            <div class="card-header">
                <h6 class="slim-card-title">Last 10 Debt installments</h6>
            </div><!-- card-header -->

            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                    <tr class="tx-10">
                        <th class="wd-10p pd-y-5">&nbsp;</th>
                        <th class="pd-y-5">Name</th>
                        <th class="pd-y-5">Phone</th>
                        <th class="pd-y-5">Amount</th>
                        <th class="pd-y-5">Should Receive At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($getMoneyDebt as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->sup_name}}</td>
                            <td class="tx-12">{{$item->sup_phone}}</td>
                            <td>
                                <span class="square-8 bg-success mg-r-5 rounded-circle"></span>
                                {{$item->money_amount}} - {{$item->sup_currency}}
                            </td>
                            <td>
                                <span class="square-8 bg-danger mg-r-5 rounded-circle"></span>
                                {{$item->should_receive_payment_at}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-lg-6 mg-t-20 mg-lg-t-0">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Owed Installments</h6>
        </div>
        <div class="card card-table">
            <div class="card-header">
                <h6 class="slim-card-title">Last 10 Owed Installments</h6>
            </div><!-- card-header -->
            <div class="table-responsive">
                <table class="table mg-b-0 tx-13">
                    <thead>
                    <tr class="tx-10">
                        <th class="wd-10p pd-y-5">&nbsp;</th>
                        <th class="pd-y-5">Name</th>
                        <th class="pd-y-5">Phone</th>
                        <th class="pd-y-5">Amount</th>
                        <th class="pd-y-5">Should Receive At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($getMoneyOwed as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->client_name}}</td>
                            <td class="tx-12">{{$item->client_phone}}</td>
                            <td>
                                <span class="square-8 bg-success mg-r-5 rounded-circle"></span>
                                {{$item->money_amount}} - {{$item->branch_currency}}
                            </td>
                            <td>
                                <span class="square-8 bg-danger mg-r-5 rounded-circle"></span>
                                {{$item->should_receive_payment_at}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
