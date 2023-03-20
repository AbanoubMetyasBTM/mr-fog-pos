<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Client Orders</h6>
</div>

<div class="card card-dash-one">
    <div class="row no-gutters">
            <div class="col-lg-3">
                <a href="{{langUrl('admin/clients-orders?date_from='.date('Y-1-1').'&date_to='.date('Y-12-31'))}}">
                    <i class="icon ion-ios-analytics-outline"></i>
                </a>
                <div class="dash-content">
                        <label class="tx-primary">This Year</label>
                        <h2>{{$clientOrdersCountThisYear}}</h2>

                </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-3">
                <a href="{{langUrl('admin/clients-orders?date_from='.date('Y-m-01').'&date_to='.date('Y-m-t'))}}">
                    <i class="icon ion-ios-pie-outline"></i>
                </a>
                <div class="dash-content">
                    <label class="tx-success">This Month</label>
                    <h2>{{$clientOrdersCountThisMonth}}</h2>
                </div><!-- dash-content  -->
            </div><!-- col-3 -->
            <div class="col-lg-3">
                <a href="{{langUrl('admin/clients-orders?date_from='.date('Y-m-d').'&date_to='.date('Y-m-d'))}}">
                    <i class="icon ion-ios-stopwatch-outline"></i>
                </a>
                <div class="dash-content">
                    <label class="tx-purple">This Day</label>
                    <h2>{{$clientOrdersCountThisDay}}</h2>
                </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-3">
                <a href="{{langUrl('admin/clients-orders')}}">
                    <i class="icon ion-stats-bars tx-teal"></i>
                </a>
                <div class="dash-content">
                    <label class="tx-danger">All</label>
                    <h2>{{$allClientOrdersCount}}</h2>
                </div><!-- dash-content -->
            </div><!-- col-3 -->
        </div><!-- row -->
</div>
