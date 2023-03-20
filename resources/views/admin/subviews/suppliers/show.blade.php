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
                <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
            </ol>
            <h6 class="slim-pagetitle"></h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>
                        <div class="row">

                            <?php


                                $normal_tags = [
                                    'supplier_name','supplier_phone'
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 4
                                );

                                echo generate_inputs_html_take_attrs($attrs);

                            ?>

                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date">Get Results</button>
                            </div>

                        </div>

                    </div>
                </div>

            </form>
        </div>

        <div class="section-wrapper">

            <?php if(havePermission("admin/suppliers_orders","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/suppliers/save")}}">
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
                                <th class="wd-15p"><span>Supplier Name</span></th>
                                <th class="wd-15p"><span>Supplier Phone</span></th>
                                <th class="wd-15p"><span>Supplier Company</span></th>
                                <th class="wd-15p"><span>Wallet Amount</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->sup_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->sup_name}}
                                </td>
                                <td>
                                    {{$item->sup_phone}}
                                </td>
                                <td>
                                    {{$item->sup_company}}
                                </td>
                                <td>
                                    {{$item->wallet_amount}}

                                    <b>
                                        {{$item->sup_currency}}
                                    </b>
                                </td>

                                <td>

                                    @include("admin.subviews.suppliers.components.supplier_actions")

                                    <button type="button" class="btn btn-primary mg-b-6" data-toggle="modal" data-target="#supplier_actions_modal_{{$item->sup_id}}">
                                        Actions
                                    </button>

                                    <?php if(havePermission("admin/suppliers","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/suppliers/save/$item->sup_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/suppliers","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\supplier\suppliers_m::class}}"
                                           data-deleteurl="{{url("/admin/suppliers/delete")}}"
                                           data-itemid="{{$item->sup_id}}">
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
