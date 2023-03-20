<div class="slim-mainpanel admin_panel">
    <div class="container-fluid">

        <?php
            $links = menuSettingsLinks();
        ?>

        <?php if(count($links)): ?>
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">Settings</h6>
            </div>

            <?php if(isset($links["Admins"])): ?>
                <a href="{{$links["Admins"]}}" class="lp_link">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Admins</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Settings"])): ?>
                <a href="{{$links["Settings"]}}" class="lp_link">
                    <i class="fa-solid fa-gears"></i>
                    <span>Settings</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Languages"])): ?>
                <a href="{{$links["Languages"]}}" class="lp_link">
                    <i class="fa-solid fa-language"></i>
                    <span>Languages</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Currencies"])): ?>
                <a href="{{$links["Currencies"]}}" class="lp_link">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <span>Currencies</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>


        <?php
            $links = menuBranchSettingsLinks();
        ?>
        <?php if(count($links)): ?>
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">
                    {{!empty($current_branch_id)?"{$current_branch_data->branch_name}":"Branches"}}
                </h6>
            </div>

            <?php if(isset($links["Branches"])): ?>
                <a href="{{$links["Branches"]}}" class="lp_link">
                    <i class="fa-solid fa-leaf"></i>
                    <span>
                        {{!empty($current_branch_id)?"Current Branch":"Branches"}}
                    </span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Branch Inventories"])): ?>
                <a href="{{$links["Branch Inventories"]}}" class="lp_link">
                    <i class="fa-solid fa-tents"></i>
                    <span>Branch Inventories</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Registers"])): ?>
                <a href="{{$links["Registers"]}}" class="lp_link">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Registers</span>
                </a>
            <?php endif; ?>

            <?php if(!empty($current_branch_id)): ?>

                <?php if(havePermission("admin/branches_prices","show_branch_prices")): ?>
                    <a href="{{url("admin/branches-prices/show-branch-prices/$current_branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-table-cells"></i>
                        <span>Branch Prices</span>
                    </a>
                <?php endif; ?>

                <?php if(havePermission("admin/transactions_log","show_log")): ?>
                    <a href="{{url("admin/transactions-log/show-log/$current_branch_data->branch_name/$current_branch_data->cash_wallet_id,$current_branch_data->debit_card_wallet_id,$current_branch_data->credit_card_wallet_id,$current_branch_data->cheque_wallet_id?transaction_type=expenses")}}" class="lp_link">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <span>Show Expenses</span>
                    </a>
                <?php endif; ?>

                <?php if(havePermission("admin/expenses","add_action")): ?>
                    <a href="{{url("admin/expenses/save?branch_id=$current_branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <span>Add Expenses</span>
                    </a>
                <?php endif; ?>


            <?php endif; ?>

        <?php endif; ?>


        <?php
            $links = menuHrLinks();
        ?>
        <?php if(count($links)): ?>
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">Hr</h6>
            </div>

            <?php if(isset($links["National Holidays"])): ?>
                <a href="{{$links["National Holidays"]}}" class="lp_link">
                    <i class="fa-solid fa-calendar"></i>
                    <span>National Holidays</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees"])): ?>
                <a href="{{$links["Employees"]}}" class="lp_link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Employees</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Actions"])): ?>
                <a href="{{$links["Employees Actions"]}}" class="lp_link">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Actions Log</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Tasks"])): ?>
                <a href="{{$links["Employees Tasks"]}}" class="lp_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tasks</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Warnings"])): ?>
                <a href="{{$links["Employees Warnings"]}}" class="lp_link">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Warnings</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Delay Requests"])): ?>
                <a href="{{$links["Employees Delay Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-hourglass-end"></i>
                    <span>Delay Requests</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Early Requests"])): ?>
                <a href="{{$links["Employees Early Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-hourglass"></i>
                    <span>Early Requests</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Sick Requests"])): ?>
                <a href="{{$links["Employees Sick Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-virus"></i>
                    <span>Sick Requests</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["Employees Vacation Requests"])): ?>
                <a href="{{$links["Employees Vacation Requests"]}}" class="lp_link" title="Vacation Requests">
                    <i class="fa-solid fa-plane-departure"></i>
                    <span>Vacation Reqs</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>


        <?php
            $links = menuMyHrLinks($current_user);
        ?>
        <?php if(count($links)): ?>

            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">My Hr</h6>
            </div>

            <?php if(isset($links["National Holidays"])): ?>
                <a href="{{$links["National Holidays"]}}" class="lp_link">
                    <i class="fa-solid fa-calendar"></i>
                    <span>National Holidays</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Tasks"])): ?>
                <a href="{{$links["My Tasks"]}}" class="lp_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>My Tasks</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Warnings"])): ?>
                <a href="{{$links["My Warnings"]}}" class="lp_link">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>My Warnings</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Delay Requests"])): ?>
                <a href="{{$links["My Delay Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-hourglass-end"></i>
                    <span>My Delay Requests</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Early Requests"])): ?>
                <a href="{{$links["My Early Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-hourglass"></i>
                    <span>My Early Reqs</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Sick Requests"])): ?>
                <a href="{{$links["My Sick Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-virus"></i>
                    <span>My Sick Reqs</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Vacation Requests"])): ?>
                <a href="{{$links["My Vacation Requests"]}}" class="lp_link">
                    <i class="fa-solid fa-plane-departure"></i>
                    <span>My Vacation Reqs</span>
                </a>
            <?php endif; ?>

            <?php if(isset($links["My Login Logout Table"])): ?>
                <a href="{{$links["My Login Logout Table"]}}" class="lp_link">
                    <i class="fa-solid fa-table"></i>
                    <span>Check In Check Out Table</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>



    </div>
</div>
