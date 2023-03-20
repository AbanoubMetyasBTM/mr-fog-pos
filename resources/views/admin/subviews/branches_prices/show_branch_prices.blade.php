<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 */

    $header_title = $branch->branch_name . " - Prices"

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branches</li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_title}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_title}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">


            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php

                                echo generate_select_tags_v2([
                                    "field_name" => "cat_id",
                                    "label_name" => "Categories",
                                    "text"       => array_merge(["all"], $all_cats->pluck("combined_name")->all()),
                                    "values"     => array_merge(["all"], $all_cats->pluck("cat_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-3",
                                    "class"      => "form-control select2_search"
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name" => "brand_id",
                                    "label_name" => "Brands",
                                    "text"       => array_merge(["all"], $all_brands->pluck("brand_name")->all()),
                                    "values"     => array_merge(["all"], $all_brands->pluck("brand_id")->all()),
                                    "data"       => $request_data ?? "",
                                    "grid"       => "col-md-3",
                                    "class"      => "form-control select2_search"
                                ]);

                                generateBTMSelect2([
                                    "field_name"              => "pro_id",
                                    "label_name"              => "Product",
                                    "data-placeholder"        => "Search by product name",
                                    "data-url"                => langUrl("admin/products/get-product-by-name?add_all=true"),
                                    "class"                   => "form-control convert_select_to_btm_select2",
                                    "hide_label"              => false,
                                    "grid"                    => "col-md-3",
                                    "data-pre_selected_text"  => $selected_product_name ?? "",
                                    "data-pre_selected_value" => $request_data->pro_id ?? "",
                                ]);

                                generateBTMSelect2([
                                    "field_name"              => "sku_id",
                                    "label_name"              => "Product SKU",
                                    "data-placeholder"        => "Search by product barcode",
                                    "data-url"                => langUrl("admin/products-sku/get-product-sku-by-barcode?add_all=true"),
                                    "class"                   => "form-control convert_select_to_btm_select2",
                                    "hide_label"              => false,
                                    "grid"                    => "col-md-3",
                                    "data-pre_selected_text"  => $selected_sku_name ?? "",
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
                                <th class="wd-15p"><span>#</span></th>
                                <th class="wd-15p" ><span>Barcode</span></th>
                                <th class="wd-15p" ><span>Product Name</span></th>
                                <th class="wd-15p" ><span>Online Item</span></th>
                                <th class="wd-15p" ><span>Online Box</span></th>
                                <th class="wd-15p" ><span>Retailer Item</span></th>
                                <th class="wd-15p" ><span>Wholesaler Item</span></th>
                                <th class="wd-15p" ><span>Retailer Box</span></th>
                                <th class="wd-15p" ><span>Wholesaler Box</span></th>
                                <th class="wd-15p" ><span>Is Active</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $pricesFields = [
                                'online_item_price',
                                'online_box_price',
                                'item_retailer_price',
                                'item_wholesaler_price',
                                'box_retailer_price',
                                'box_wholesaler_price',
                            ];
                        ?>

                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    Box: {{$item->ps_box_barcode}} <br>
                                    Item: {{$item->ps_item_barcode}}

                                </td>
                                <td>
                                    <a href="{{url("/admin/branches-prices/show-branch-prices/{$branch->branch_id}?pro_id={$item->pro_id}")}}">
                                        {{$item->product_name}}
                                    </a> -
                                    {{$item->ps_selected_variant_type_values_text}}
                                </td>

                                @foreach($pricesFields as $field)
                                    <td >
                                        <div>
                                            <?php
                                                echo generate_self_edit_input(
                                                    $url = url("admin/branches-prices/update-branch-prices"),
                                                    $item,
                                                    $item_primary_col="id",
                                                    $item_edit_col="$field",
                                                    $modal_path = \App\models\branch\branch_prices_m::class,
                                                    $input_type = "number",
                                                    $label = "Click To Edit",
                                                    $func_after_edit = ""
                                                );
                                            ?>
                                        </div>


                                        <span class="text-info">
                                            {{$branch->branch_currency}}
                                        </span>

                                    </td>

                                @endforeach
                                <td >
                                    <?php
                                        echo generate_multi_accepters(
                                            $accepturl = "",
                                            $item,
                                            $item_primary_col="id",
                                            $accept_or_refuse_col="is_active",
                                            $model = \App\models\branch\branch_prices_m::class,
                                            $accepters_data = [
                                                "0" => "Not Active",
                                                "1" => "Active",
                                            ]
                                        );
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                {!! $results->links()  !!}

                <?php else : ?>
                    @include('global_components.no_results_found')
                <?php endif; ?>


            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
