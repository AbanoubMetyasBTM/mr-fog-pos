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
                <li class="breadcrumb-item active" aria-current="page">Inventories Products</li>
            </ol>
            <h6 class="slim-pagetitle">
                Inventories Products
            </h6>

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

                                echo generate_select_tags_v2([
                                    "field_name" => "quantity_limit",
                                    "label_name" => "Less than Qty Limit",
                                    "text"       => ["All", "Yes", "No"],
                                    "values"     => ["all", "yes", "no"],
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-4",
                                ]);

                                 generateBTMSelect2([
                                     "field_name"              => "sku_id",
                                     "label_name"              => "Product Barcode",
                                     "data-placeholder"        => "Search by product barcode",
                                     "data-url"                => langUrl("admin/products-sku/get-product-sku-by-barcode?add_all=true"),
                                     "class"                   => "form-control convert_select_to_btm_select2",
                                     "hide_label"              => false,
                                     "grid"                    => "col-md-4",
                                     "data"                    => $request_data ?? "",
                                     "data-pre_selected_text"  => isset($product_sku_obj) ?$product_sku_obj->product_name : "",
                                     "data-pre_selected_value" => $request_data->sku_id ?? "",
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

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-10p"><span>#</span></th>
                                <th class="wd-10p"><span>Inventory</span></th>
                                <th class="wd-10p"><span>Product</span></th>
                                <th class="wd-10p"><span>Category</span></th>
                                <th class="wd-10p"><span>Brand</span></th>
                                <th class="wd-10p"><span>Product Box Qty</span></th>
                                <th class="wd-10p"><span>Product Item Qty</span></th>
                                <th class="wd-10p"><span>Total Product Items Qty</span></th>
                                <th class="wd-10p"><span>Product Qty Limit</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->ip_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->inv_name}}</td>
                                <td>
                                    {{$item->product_name}} -
                                    {{$item->ps_selected_variant_type_values_text}}
                                </td>
                                <td>{{$item->cat_name}}</td>
                                <td>{{$item->brand_name}}</td>
                                <td style="text-align: center">{{$item->ip_box_quantity}}</td>
                                <td style="text-align: center">{{$item->ip_item_quantity}}</td>
                                <td style="text-align: center">{{$item->total_items_quantity}}</td>
                                <td style="text-align: center">
                                    <?php
                                        echo generate_self_edit_input(
                                            $url = url("admin/inventories-products/update-quantity-limit"),
                                            $item,
                                            $item_primary_col="ip_id",
                                            $item_edit_col="quantity_limit",
                                            $modal_path = \App\models\inventory\inventories_products_m::class,
                                            $input_type = "number",
                                            $label = "Click To Edit",
                                            $func_after_edit = ""
                                        );

                                    ?>
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
