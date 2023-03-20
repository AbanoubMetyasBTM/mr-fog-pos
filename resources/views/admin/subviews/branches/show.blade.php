<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branches</li>
            </ol>
            <h6 class="slim-pagetitle">Branches</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/branches","add_action") && $current_user->user_type!="employee"): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/branches/save")}}">
                        Add New <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>#</span></th>
                                <th class="wd-15p"><span>Branch Name</span></th>
                                <th class="wd-15p"><span>Cash Wallet</span></th>
                                <th class="wd-15p"><span>Debit Card Wallet</span></th>
                                <th class="wd-15p"><span>Credit Card Wallet</span></th>
                                <th class="wd-15p"><span>Cheque Wallet</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->branch_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->branch_name}}</td>
                                <td>
                                    {{$item->cash_wallet_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td>
                                    {{$item->debit_card_wallet_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td>
                                    {{$item->credit_card_wallet_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td>
                                    {{$item->cheque_wallet_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>

                                <td>

                                    @include("admin.subviews.branches.components.branch_actions")

                                    <button type="button" class="btn btn-primary mg-b-6" data-toggle="modal" data-target="#branch_actions_modal_{{$item->branch_id}}">
                                        Actions
                                    </button>


                                    <?php if(havePermission("admin/branches","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/branches/save/$item->branch_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/branches","delete_action") && $current_user->user_type!="employee"): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\branch\branches_m::class}}"
                                           data-deleteurl="{{url("/admin/branches/delete")}}"
                                           data-itemid="{{$item->branch_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
