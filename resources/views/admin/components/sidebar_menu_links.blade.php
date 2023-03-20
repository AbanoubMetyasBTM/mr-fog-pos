<div class="slim-sidebar">
    <label class="sidebar-label"></label>

    <h2 class="logo_header">
        <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">
            <span>
                <img src="{{url("/")}}/public/images/logo.png" style="max-height: 65px;">
            </span>

            Mr Fog
        </a>
    </h2>

    <div class="dropdown dropdown-c loggedin_user_dropdown mb-2">
        <a type="button" href="{{url("/admin/branches")}}" class="logged-user {{isset($current_branch_data)?"active":""}}">
            <span>{{isset($current_branch_data)?$current_branch_data->branch_name:"Main Panel"}}</span>
        </a>
    </div>

    <div class="dropdown dropdown-c loggedin_user_dropdown mb-2">
        <button href="#" class="logged-user" data-toggle="dropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span>{{$current_user->full_name}}</span>
            <i class="fa fa-angle-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <nav class="nav">

                <?php if(
                    Auth::user()->user_role == "admin" &&
                    !empty(session()->get('login_to_branch_id'))
                ): ?>
                    <a class="nav-link do_not_ajax" href="{{url("admin/branches/back-to-main-admin-panel")}}">
                        Back to Main Admin Panel
                    </a>
                <?php endif; ?>

                <a href="{{url("logout")}}" class="nav-link do_not_ajax logout_btn">
                    <i class="icon ion-forward"></i> Logout
                </a>
            </nav>
        </div><!-- dropdown-menu -->
    </div><!-- dropdown -->


    <ul class="nav nav-sidebar">

        <li class="sidebar-nav-item">
            <a href="{{url("/admin/dashboard")}}" class="sidebar-nav-link">
                <i class="fa fa-home"></i>
                Dashboard
            </a>
        </li>

        <?php if(count(menuSalesLinks())): ?>
            <li class="sidebar-nav-item">
                <a href="{{url("/admin/menu-sales")}}" class="sidebar-nav-link">
                    <img src="{{url("/")}}/public/front/images/sales.png" alt="" width="25">
                    Sales
                </a>
            </li>
        <?php endif; ?>

        <?php if(count(menuInventoryLinks())): ?>
            <li class="sidebar-nav-item">
                <a href="{{url("/admin/menu-inventory")}}" class="sidebar-nav-link">
                    <img src="{{url("/")}}/public/front/images/inventory.png" alt="" width="25">
                    Inventory
                </a>
            </li>
        <?php endif; ?>

        <?php if(count(menuCustomersLinks())): ?>
            <li class="sidebar-nav-item">
                <a href="{{url("/admin/menu-customers")}}" class="sidebar-nav-link">
                    <img src="{{url("/")}}/public/front/images/customers.png" alt="" width="25">
                    Customers
                </a>
            </li>
        <?php endif; ?>

        <li class="sidebar-nav-item">
            <a href="{{url("/admin/menu-reports")}}" class="sidebar-nav-link">
                <img src="{{url("/")}}/public/front/images/reports.png" alt="" width="25">
                Reports
            </a>
        </li>

        <?php if(
            count(menuSettingsLinks()) ||
            count(menuBranchSettingsLinks()) ||
            count(menuHrLinks()) ||
            count(menuMyHrLinks($current_user))
        ): ?>
            <li class="sidebar-nav-item">
                <a href="{{url("/admin/menu-settings")}}" class="sidebar-nav-link">
                    <img src="{{url("/")}}/public/front/images/cogs.png" alt="" width="25">

                    Settings
                </a>
            </li>
        <?php endif; ?>


    </ul>
</div><!-- slim-sidebar -->
