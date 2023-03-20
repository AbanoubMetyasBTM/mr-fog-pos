<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        <?php if(
            Auth::user()->user_role == "admin" &&
            !empty(session()->get('login_to_branch_id'))
        ): ?>
            <a class="btn btn-primary mg-b-6 do_not_ajax" href="{{url("admin/branches/back-to-main-admin-panel")}}">
                Back to Main Admin Panel
            </a>
        <?php endif; ?>

        @include("admin.subviews.dashboard.components.branch_admin_alerts")

        @include("admin.subviews.dashboard.components.client_orders_statistics")
        @include("admin.subviews.dashboard.components.clients_count_statistics")
        @include("admin.subviews.dashboard.components.gift_cards_count_statistics")

        @include("admin.subviews.dashboard.components.gift_cards_balances")
        @include("admin.subviews.dashboard.components.clients_balances")


        <div class="row">
            <div class="col-lg-12 mg-t-20 mg-lg-t-0">
                @include("admin.subviews.dashboard.components.last_orders")
            </div>
        </div>

        @include("admin.subviews.dashboard.components.critical_quantities")


    </div>
</div>

