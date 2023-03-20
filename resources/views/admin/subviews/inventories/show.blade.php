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
                <li class="breadcrumb-item active" aria-current="page">Inventories</li>
            </ol>
            <h6 class="slim-pagetitle">Inventories</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/inventories","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories/save")}}">
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
                                <th class="wd-15p"><span>Inventory Name</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->inv_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->inv_name}}

                                </td>

                                <td>

                                    <?php if(havePermission("admin/inventories_products","show_inventories_products")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories-products/show?inventory_id=$item->inv_id")}}">
                                            Show Inventory Products
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/inventories_log", "show_logs")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories-log/show-log?inventory_id=$item->inv_id")}}">
                                            Show Inventory History
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/inventories","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories/save/$item->inv_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/inventories","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\inventory\inventories_m::class}}"
                                           data-deleteurl="{{url("/admin/inventories/delete")}}"
                                           data-itemid="{{$item->inv_id}}">
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
