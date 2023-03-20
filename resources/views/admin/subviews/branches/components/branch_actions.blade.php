<div class="modal fade" id="branch_actions_modal_{{$item->branch_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{$item->branch_name}} - Actions
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="slim-pageheader pt-0 pb-0">
                    <ol class="breadcrumb slim-breadcrumb">
                    </ol>
                    <h6 class="slim-pagetitle">
                        Actions
                    </h6>
                </div>

                <?php if(
                    havePermission("admin/branches","access_branch") &&
                    empty(session()->get('login_to_branch_id'))
                ): ?>
                    <a href="{{url("admin/branches/access-branch-as-branch-admin/$item->branch_id")}}" class="lp_link do_not_ajax">
                        <i class="fa-solid fa-door-open"></i>
                        <span>Access Branch</span>
                    </a>
                <?php endif; ?>

                <?php if(havePermission("admin/clients_orders", "show_action")): ?>
                    <a href="{{url("admin/clients-orders?branch_id=$item->branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-folder"></i>
                        <span>Client Orders</span>
                    </a>
                <?php endif; ?>

                <?php if(havePermission("admin/branches_prices","show_branch_prices")): ?>
                    <a href="{{url("admin/branches-prices/show-branch-prices/$item->branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-table-cells"></i>
                        <span>Branch Prices</span>
                    </a>
                <?php endif; ?>

                <?php if(havePermission("admin/branches_inventories","show_action")): ?>
                    <a href="{{url("admin/branches-inventories?branch_id=$item->branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-tents"></i>
                        <span>Show Branch Inventories</span>
                    </a>
                <?php endif; ?>


                <?php if(havePermission("admin/transactions_log","show_log")): ?>
                    <a href="{{url("admin/transactions-log/show-log/$item->branch_name/$item->cash_wallet_id,$item->debit_card_wallet_id,$item->credit_card_wallet_id,$item->cheque_wallet_id?transaction_type=expenses")}}" class="lp_link">
                        <i class="fa-solid fa-coins"></i>
                        <span>Show Expenses</span>
                    </a>
                <?php endif; ?>


                <?php if(havePermission("admin/expenses","add_action")): ?>
                    <a href="{{url("admin/expenses/save?branch_id=$item->branch_id")}}" class="lp_link">
                        <i class="fa-solid fa-coins"></i>
                        <i class="fa-solid fa-plus abs_i"></i>
                        <span>Add Expenses</span>
                    </a>
                <?php endif; ?>


                <div class="row">
                    <?php if(havePermission("admin/transactions_log","show_log")): ?>

                        <?php
                            $walletTypes = [
                                "Cash Wallet"        => "cash_wallet_id",
                                "Debit Card Wallet"  => "debit_card_wallet_id",
                                "Credit Card Wallet" => "credit_card_wallet_id",
                                "Cheque Wallet"      => "cheque_wallet_id",
                            ];
                        ?>

                        <?php foreach($walletTypes as $walletTitle=>$walletCol): ?>

                            <div class="col-md-6">
                                <div class="slim-pageheader pt-0 pb-0">
                                    <ol class="breadcrumb slim-breadcrumb">
                                    </ol>
                                    <h6 class="slim-pagetitle">
                                        {{$walletTitle}}
                                    </h6>
                                </div>


                                <a href="{{url("admin/transactions-log/show-log/$item->branch_name/".$item->{$walletCol})}}" class="lp_link">
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Transaction Log</span>
                                </a>

                                <a href="{{url("admin/transactions-log/deposit-money/$item->branch_name/".$item->{$walletCol})}}" class="lp_link">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Deposit</span>
                                </a>

                                <a href="{{url("admin/transactions-log/withdraw-money/$item->branch_name/".$item->{$walletCol})}}" class="lp_link">
                                    <i class="fa-solid fa-minus"></i>
                                    <span>WithDraw</span>
                                </a>
                            </div>


                        <?php endforeach; ?>


                    <?php endif; ?>
                </div>


            </div>
        </div>
    </div>
</div>
