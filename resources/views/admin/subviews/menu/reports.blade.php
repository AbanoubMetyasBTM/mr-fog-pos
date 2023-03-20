<div class="row">
    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">Sales & Refunds</div>
            <div class="panel-body">

                <?php if(havePermission("admin/clients_orders", "show_action")): ?>
                    <?php
                        $dayFrom7Pasts = \Carbon\Carbon::now()->subDays(7)->toDateString();
                    ?>

                    <a href="{{url("admin/clients-orders")}}">
                        All Orders
                    </a> <br>

                    <a href="{{url("admin/clients-orders?date_from=".date("Y-m-d")."&date_to=".date("Y-m-d"))}}">
                        Today's Orders
                    </a> <br>

                    <a href="{{url("admin/clients-orders?date_from=".$dayFrom7Pasts."&date_to=".date("Y-m-d"))}}">
                        This Week Orders
                    </a> <br>

                    <a href="{{url("admin/clients-orders?date_from=".date("Y-m-01")."&date_to=".date("Y-m-t"))}}">
                        This Month Orders
                    </a> <br>
                <?php endif; ?>

                <hr>

                <?php if(havePermission("admin/reports_client_orders_count", "show_report")): ?>
                    <a href="{{url("admin/reports/client-orders-count-monthly")}}">
                        Sales Count - By Branches
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_client_orders_sum", "show_report")): ?>
                    <a href="{{url("admin/reports/client-orders-sum-monthly")}}">
                        Sales Sum - By Branches
                    </a> <br>
                <?php endif; ?>

                <hr>


                <?php if(havePermission("admin/reports_sold_products", "show_report")): ?>
                    <a href="{{url("admin/reports/sold-products-monthly")}}">
                        Sales Count - By products
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_sold_products_sales_sum", "show_report")): ?>
                    <a href="{{url("admin/reports/sold-products-sales-sum-monthly")}}">
                        Sales Sum - By products
                    </a> <br>
                <?php endif; ?>

                <hr>

                <?php if(havePermission("admin/reports_sold_products_sku", "show_report")): ?>
                    <a href="{{url("admin/reports/sold-products-sku-monthly")}}">
                        Sales Count - By product Skus
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_sold_products_sku_sales_sum", "show_report")): ?>
                    <a href="{{url("admin/reports/sold-products-sku-sales-sum-monthly")}}">
                        Sales Sum - By product Skus
                    </a> <br>
                <?php endif; ?>

                <hr>

                <?php if(havePermission("admin/reports_orders_items_categories_sales_quantity", "show_report")): ?>
                    <a href="{{url("admin/reports/orders-items-categories-quantities-monthly")}}">
                        Sales Count - By Category
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_orders_items_categories_sales_sum", "show_report")): ?>
                    <a href="{{url("admin/reports/orders-items-categories-sales-sum-monthly")}}">
                        Sales Sum - By Category
                    </a> <br>
                <?php endif; ?>

                <hr>

                <?php if(havePermission("admin/reports_orders_items_brands_sales_quantity", "show_report")): ?>
                    <a href="{{url("admin/reports/orders-items-brands-quantities-monthly")}}">
                        Sales Count - By Brand
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_orders_items_brands_sales_sum", "show_report")): ?>
                    <a href="{{url("admin/reports/orders-items-brands-sales-sum-monthly")}}">
                        Sales Sum - By Brand
                    </a> <br>
                <?php endif; ?>

                <hr>

                <?php if(false): ?>
                    <a href="">
                        Order Taxes
                    </a> <br>
                <?php endif; ?>


            </div>
        </div>

    </div>
    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">INVENTORY</div>
            <div class="panel-body">

                <?php if(havePermission("admin/inventories_products", "show_inventories_products")): ?>
                    <a href="{{url("admin/inventories-products/show")}}">
                        Current Inventory
                    </a> <br>
                <?php endif; ?>

                <?php if(havePermission("admin/inventories_log", "show_logs")): ?>
                    <a href="{{url("admin/inventories-log/show-log?log_type=add_inventory")}}">
                        Received All
                    </a> - the inventory you've received during a time period. <br>

                    <a href="{{url("admin/inventories-log/show-log?log_type=return_order")}}">
                        Returned All
                    </a> - the inventory you've returned during a time period. <br>

                    <a href="{{url("admin/inventories-log/show-log")}}">
                        Logs All
                    </a> - changes to inventory <br>
                <?php endif; ?>

                <?php if(havePermission("admin/reports_total_quantities_at_inventory", "show_report")): ?>
                    <a href="{{url("admin/reports/inventory-quantities-grouped-by-category")}}">
                        Grouped Assets - Category
                    </a> <br>

                    <a href="{{url("admin/reports/inventory-quantities-grouped-by-brand")}}">
                        Grouped Assets - Brand
                    </a> <br>
                <?php endif; ?>

            </div>
        </div>

        <?php if(havePermission("admin/reports_count_client", "show_report")): ?>
            <div class="panel panel-default">
                <div class="panel-heading">Clients</div>
                <div class="panel-body">

                    <a href="{{url("admin/reports/count-client-monthly")}}">
                        Clients Count
                    </a> <br>

                </div>
            </div>
        <?php endif; ?>

        <?php if(havePermission("admin/registers", "show_action")): ?>
            <div class="panel panel-default">
                <div class="panel-heading">Registers</div>
                <div class="panel-body">

                    <a href="{{url("admin/registers")}}">
                        Show Registers
                    </a> <br>

                </div>
            </div>
        <?php endif; ?>

    </div>
</div>


