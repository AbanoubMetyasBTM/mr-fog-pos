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
                <li class="breadcrumb-item active" aria-current="page">Clients</li>
            </ol>
            <h6 class="slim-pagetitle"></h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">

                        <?php
                            $hideMainFormBuildTitle = true;
                            $builderObj         = new \App\form_builder\filters\ClientFilterBuilder();
                            $item_data          = $request_data ?? "";
                        ?>
                        @include("general_form_blocks.main_form")

                        <div class="row">

                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date">Get Results</button>
                            </div>

                        </div>

                    </div>
                </div>

            </form>
        </div>

        <div class="section-wrapper">

            <?php if(havePermission("admin/clients","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/clients/save")}}">
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
                                <th class="wd-15p"><span>Client Name</span></th>
                                <th class="wd-15p"><span>Branch Name</span></th>
                                <th class="wd-15p"><span>Client Type</span></th>
                                <th class="wd-15p"><span>Client Phone</span></th>
                                <th class="wd-15p"><span>Client Email</span></th>
                                <th class="wd-15p"><span>Client Loyal Points</span></th>
                                <th class="wd-15p"><span>Wallet</span></th>
                                <th class="wd-15p"><span>Total Orders Count</span></th>
                                <th class="wd-15p"><span>Total Orders Amount</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->client_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->client_name}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->client_type}}
                                </td>
                                <td>
                                    {{$item->client_phone}}
                                </td>
                                <td>
                                    {{$item->client_email}}
                                </td>
                                <td>
                                    {{$item->points_wallet_value}}
                                </td>
                                <td>
                                    {{$item->wallet_amount}}
                                    <b>{{$item->branch_currency}}</b>
                                </td>
                                <td>{{$item->client_total_orders_count}}</td>
                                <td>{{$item->client_total_orders_amount}}</td>

                                <td>

                                    @include("admin.subviews.clients.components.client_actions")

                                    <button type="button" class="btn btn-primary mg-b-6" data-toggle="modal" data-target="#client_actions_modal_{{$item->client_id}}">
                                        Actions
                                    </button>

                                    <?php if(havePermission("admin/clients","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/clients/save/$item->client_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/clients","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\client\clients_m::class}}"
                                           data-deleteurl="{{url("/admin/clients/delete")}}"
                                           data-itemid="{{$item->client_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    @include('global_components.pagination')

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
