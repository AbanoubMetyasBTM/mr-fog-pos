<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Clients Count</h6>
</div>

<div class="card card-dash-one">
    <div class="row no-gutters">
        <div class="col-lg-3">
            <i class="icon ion-ios-analytics-outline"></i>
            <div class="dash-content">
                <label class="tx-primary">This Year</label>
                <h2>{{$countClientsThisYear}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
        <div class="col-lg-3">
            <i class="icon ion-ios-pie-outline"></i>
            <div class="dash-content">
                <label class="tx-success">This Month</label>
                <h2>{{$countClientsThisMonth}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
        <div class="col-lg-3">
            <i class="icon ion-ios-stopwatch-outline"></i>
            <div class="dash-content">
                <label class="tx-purple">This Day</label>
                <h2>{{$countClientsThisDay}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
        <div class="col-lg-3">
            <i class="icon ion-stats-bars tx-teal"></i>
            <div class="dash-content">
                <label class="tx-danger">All</label>
                <h2>{{$allCountClients}}</h2>
            </div><!-- dash-content -->
        </div><!-- col-3 -->
    </div><!-- row -->
</div>
