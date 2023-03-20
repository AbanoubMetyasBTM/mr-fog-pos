<?php
    /**
     *
     * @var $results \Illuminate\Support\Collection
     * @var $all_inventories \Illuminate\Support\Collection
     */

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventories History</li>
            </ol>
            <h6 class="slim-pagetitle">Inventories History</h6>

        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">


            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php

                                echo generate_select_tags_v2([
                                    "field_name" => "inventory_id",
                                    "label_name" => "Inventory Name",
                                    "text"       => array_merge(["all"], $all_inventories->pluck("inv_name")->all()),
                                    "values"     => array_merge(["all"], $all_inventories->pluck("inv_id")->all()),
                                    "class"      => "form-control select2_search",
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-4",
                                ]);

                                 echo generate_select_tags_v2([
                                    "field_name" => "cat_id",
                                    "label_name" => "Categories",
                                    "text"       => array_merge(["all"], $all_cats->pluck("combined_name")->all()),
                                    "values"     => array_merge(["all"], $all_cats->pluck("cat_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-4",
                                    "class"      => "form-control select2_search"
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name" => "brand_id",
                                    "label_name" => "Brands",
                                    "text"       => array_merge(["all"], $all_brands->pluck("brand_name")->all()),
                                    "values"     => array_merge(["all"], $all_brands->pluck("brand_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-4",
                                    "class"      => "form-control select2_search"
                                ]);


                                 generateBTMSelect2([
                                    "field_name"              => "sku_id",
                                    "label_name"              => "Product Barcode",
                                    "data-placeholder"        => "Search by product barcode",
                                    "data-url"                => langUrl("admin/products-sku/get-product-sku-by-barcode?add_all=true"),
                                    "class"                   => "form-control convert_select_to_btm_select2",
                                    "data"                    => $request_data ?? "",
                                    "hide_label"              => false,
                                    "grid"                    => "col-md-4",
                                    "data-pre_selected_text"  => isset($product_sku_obj) ?$product_sku_obj->product_name : "",
                                    "data-pre_selected_value" => $request_data->sku_id ?? "",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "log_type",
                                    "label_name"              => "Log Type",
                                    "text"                    => array_merge(["all"],array_values(\App\models\inventory\inventories_logs_m::$invLogTypes)),
                                    "values"                  => array_merge(["all"],array_keys(\App\models\inventory\inventories_logs_m::$invLogTypes)),
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);




                                $normal_tags = [
                                        "log_id", "date_from", "date_to"
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 4
                                );


                                $attrs[3]["date_from"]    = "date";
                                $attrs[3]["date_to"]      = "date";

                                echo
                                generate_inputs_html_take_attrs($attrs);

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

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-10-force"><span>#</span></th>
                                <th class="wd-10-force"><span>Inventory</span></th>
                                <th class="wd-10-force"><span>Product</span></th>
                                <th class="wd-10-force"><span>Log Type</span></th>
                                <th class="wd-10-force"><span>Note</span></th>
                                <th class="wd-10-force"><span>Box Q</span></th>
                                <th class="wd-10-force"><span>Items Q</span></th>
                                <th class="wd-10-force"><span>Created at</span></th>
                                <th class="wd-10-force"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}" class="{{$item->log_operation=="increase"?"table-success":"table-danger"}}">
                                <td>{{$item->id}}</td>
                                <td>{{$item->inv_name}}</td>
                                <td>{{$item->product_name}}</td>
                                <td>{{\App\models\inventory\inventories_logs_m::$invLogTypes[$item->log_type]??""}}</td>
                                <td>{{$item->log_desc}}</td>
                                <td>{{$item->log_box_quantity}}</td>
                                <td>{{$item->log_item_quantity}}</td>
                                <td>{{$item->created_at}}</td>

                                <td>
                                    <?php
                                        $createdAtAfterHour = $item->created_at->addHour();

                                        if(
                                            havePermission("admin/inventories_log","add_invalid_entry") &&
                                            $item->is_refunded == 0 &&
                                            $item->log_type == 'add_inventory' &&
                                            $item->log_operation == 'increase' &&
                                            $createdAtAfterHour >= now()
                                        ):
                                    ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/inventories-log/invalid-entry/" .$item->id)}}">
                                            Invalid Entry <i class="fa fa-plus"></i>
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
