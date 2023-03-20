<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb"></ol>
    <h6 class="slim-pagetitle">Clients Balances</h6>
</div>

<div class="card card-dash-one">
    <div class="row no-gutters">
        <?php foreach ($clientsTotalWalletAmountGroupedByCurrency as $key=>$item): ?>
        <div class="col-lg-3">
            <i class="icon ion-ios-calculator"></i>
            <div class="dash-content">
                <label class="tx-primary">{{$item->branch_currency}}</label>
                <h2>{{$item->total_amount_clients}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
        <?php endforeach; ?>
    </div>
</div>
