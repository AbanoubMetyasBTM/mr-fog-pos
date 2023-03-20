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
                <li class="breadcrumb-item active" aria-current="page">Branches Inventories</li>
            </ol>
            <h6 class="slim-pagetitle">Branches Inventories</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">


            <form id="save_form" action="{{url("admin/branches-inventories")}}" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php

                                echo generate_select_tags_v2([
                                    "field_name"              => "branch_id",
                                    "label_name"              => "Select Branch Name",
                                    "text"                    => array_merge(['All'], $branches->pluck("branch_name")->all()),
                                    "values"                  => array_merge(['all'], $branches->pluck("branch_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "inventory_id",
                                    "label_name"              => "Select Inventory Name",
                                    "text"                    => array_merge(['All'], $inventories->pluck("inv_name")->all()),
                                    "values"                  => array_merge(['all'], $inventories->pluck("inv_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                            echo generate_select_tags_v2([
                                "field_name"              => "is_main_inventory",
                                "label_name"              => "Is Main Inventory",
                                "text"                    => array_merge(['All'], ['Yes'], ['No']),
                                "values"                  => array_merge(['all'], ['1'], ['0']),
                                "class"                   => "form-control select2_primary",
                                "data"                    => $request_data ?? "",
                                "grid"                    => "col-md-4",
                            ]);
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

            <?php if(havePermission("admin/branches_inventories","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/branches-inventories/save")}}">
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
                                <th class="wd-15p"><span>inventory Name</span></th>
                                <th class="wd-15p"><span>Is Main Inventory</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->inv_name}}
                                </td>
                                <td style="text-align: center">
                                    <?php
                                        echo $item->is_main_inventory == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?>
                                </td>
                                <td>

                                    <?php if(havePermission("admin/inventories_products","show_inventories_products")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories-products/show?inventory_id=$item->inventory_id")}}">
                                            Show Inventory Products
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/inventories_log", "show_logs")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories-log/show-log?inventory_id=$item->inventory_id")}}">
                                            Show Inventory History
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/branches_inventories","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/branches-inventories/save/$item->id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/branches_inventories","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\branch\branches_m::class}}"
                                           data-deleteurl="{{url("/admin/branches-inventories/delete")}}"
                                           data-itemid="{{$item->id}}">
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
