<div class="modal fade" id="supplier_actions_modal_{{$item->sup_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{$item->sup_name}} - Actions
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

                        <?php if(havePermission("admin/suppliers_orders","show_action")): ?>
                            <a href="{{url("admin/suppliers-orders?sup_id=$item->sup_id")}}" class="lp_link">
                                <i class="fa-solid fa-truck-field"></i>
                                <span>Supplier Orders</span>
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
                            $walletName = $item->sup_name;
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
                            $walletName = $item->sup_name;
                        ?>
                        @include("admin.subviews.clients.components.installments_actions")


                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
