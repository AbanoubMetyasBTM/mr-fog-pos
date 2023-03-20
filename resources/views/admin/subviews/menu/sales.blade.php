<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Orders</h6>
        </div>

        <?php
            $links = menuSalesLinks();
        ?>

        <?php if(havePermission("admin/clients_orders","add_action") && $current_user->user_role!="admin"): ?>
            <a href="{{url("admin/clients-orders/add-order")}}" class="lp_link">
                <i class="fa fa-plus"></i>
                <span>New Order</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Clients Orders"])): ?>
            <a href="{{$links["Clients Orders"]}}" class="lp_link">
                <i class="fa-solid fa-folder"></i>
                <span>Orders</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Pick Up Clients Orders Today"])): ?>
            <a href="{{$links["Pick Up Clients Orders Today"]}}" class="lp_link">
                <i class="fa fa-truck"></i>
                <span>Pick Up - Today</span>
            </a>
        <?php endif; ?>

        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Gift Cards</h6>
        </div>

        <?php if(havePermission("admin/gift_card_templates","show_action")): ?>
            <a href="{{url("/admin/gift-card-templates")}}" class="lp_link">
                <i class="fa fa-credit-card"></i>
                <span>Gift Card Templates</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Gift Cards"])): ?>
            <a href="{{$links["Gift Cards"]}}" class="lp_link">
                <i class="fa fa-credit-card"></i>
                <span>Gift Cards</span>
            </a>
        <?php endif; ?>

        <?php if(havePermission("admin/gift_cards","add_action") && $current_branch_id != null): ?>
            <a href="{{url("admin/gift-cards/add-card")}}" class="lp_link">
                <i class="fa fa-plus"></i>
                <span>New Gift Card</span>
            </a>
        <?php endif; ?>




        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Discounts</h6>
        </div>

        <?php if(isset($links["Coupons"])): ?>
            <a href="{{$links["Coupons"]}}" class="lp_link">
                <i class="fa-solid fa-money-check-dollar"></i>
                <span>Coupons</span>
            </a>
        <?php endif; ?>

        <?php if(isset($links["Product Promotions"])): ?>
            <a href="{{$links["Product Promotions"]}}" class="lp_link">
                <i class="fa-solid fa-percent"></i>
                <span>Promotions</span>
            </a>
        <?php endif; ?>




    </div>
</div>
