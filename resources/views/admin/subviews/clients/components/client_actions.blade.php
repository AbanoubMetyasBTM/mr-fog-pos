<div class="modal fade" id="client_actions_modal_{{$item->client_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{$item->client_name}} - Actions
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="slim-pageheader pt-0 pb-0">
                            <ol class="breadcrumb slim-breadcrumb">
                            </ol>
                            <h6 class="slim-pagetitle">
                                Actions
                            </h6>
                        </div>

                        <?php if(havePermission("admin/clients_orders", "show_action")): ?>
                            <a href="{{url("admin/clients-orders?client_id=$item->client_id")}}" class="lp_link">
                                <i class="fa-solid fa-folder"></i>
                                <span>Client Orders</span>
                            </a>
                        <?php endif; ?>

                        <?php if(havePermission("admin/transactions_log", "show_log")): ?>
                            <a href="{{url("admin/transactions-log/show-log/$item->client_name points log/$item->points_wallet_id")}}" class="lp_link">
                                <i class="fa-regular fa-file-lines"></i>
                                <span>Loyalty Points log</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">

                        <div class="slim-pageheader pt-0 pb-0">
                            <ol class="breadcrumb slim-breadcrumb">
                            </ol>
                            <h6 class="slim-pagetitle">
                                Wallet
                            </h6>
                        </div>

                        <?php
                            $walletName = $item->client_name;
                        ?>
                        @include("admin.subviews.clients.components.wallet_actions")



                    </div>

                    <div class="col-md-4">

                        <div class="slim-pageheader pt-0 pb-0">
                            <ol class="breadcrumb slim-breadcrumb">
                            </ol>
                            <h6 class="slim-pagetitle">
                                Installments
                            </h6>
                        </div>


                        <?php
                            $walletName = $item->client_name;
                        ?>
                        @include("admin.subviews.clients.components.installments_actions")


                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
