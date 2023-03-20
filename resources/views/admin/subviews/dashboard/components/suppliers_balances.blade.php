<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb"></ol>
    <h6 class="slim-pagetitle">Suppliers Balances</h6>
</div>

<div class="card card-dash-one">
    <div class="row no-gutters">
        <?php foreach ($suppliersTotalWalletAmountGroupedByCurrency as $key=>$item): ?>
        <div class="col-lg-3">
            <i class="icon ion-ios-calculator"></i>
            <div class="dash-content">
                <label class="tx-primary">{{$item->currency}}</label>
                <h2>{{$item->suppliers_total_amount}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
        <?php endforeach; ?>
    </div>
</div>
