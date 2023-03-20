<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        @include("admin.subviews.dashboard.components.admin_alerts")

        @include("admin.subviews.dashboard.components.client_orders_statistics")
        @include("admin.subviews.dashboard.components.clients_count_statistics")
        @include("admin.subviews.dashboard.components.gift_cards_count_statistics")

        @include("admin.subviews.dashboard.components.gift_cards_balances")
        @include("admin.subviews.dashboard.components.clients_balances")
        @include("admin.subviews.dashboard.components.suppliers_balances")


        <div class="row">

            <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                @include("admin.subviews.dashboard.components.last_orders")
            </div>

            <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                @include("admin.subviews.dashboard.components.orders_today_grouped_by_branches")
            </div>

        </div>

        @include("admin.subviews.dashboard.components.installments")

        @include("admin.subviews.dashboard.components.critical_quantities")


    </div>
</div>

