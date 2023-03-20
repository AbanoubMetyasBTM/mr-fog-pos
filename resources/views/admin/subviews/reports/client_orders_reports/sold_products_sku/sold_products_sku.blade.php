<?php
/**
 * @var \Illuminate\Support\Collection $report_sold_products_buy
 * @var \Illuminate\Support\Collection $all_branches
 * @var \Illuminate\Support\Collection $all_cats
 * @var \Illuminate\Support\Collection $report_sold_products_return
 * @var \Illuminate\Support\Collection $all_brands
 * @var boolean $show_input_year
 * @var boolean $show_from_date_and_to_date_inputs
 */
?>
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sales Count By Products SKUS</li>
            </ol>
            <h6 class="slim-pagetitle">Sales Count By Products SKUS {{$report_type_date}}</h6>
        </div><!-- slim-pageheader -->

        <?php
            $dayFrom7Pasts = \Carbon\Carbon::now()->subDays(7)->toDateString();
        ?>

        <a href="{{url("admin/reports/sold-products-sku-yearly")}}" class="btn btn-info" >Yearly</a>
        <a href="{{url("admin/reports/sold-products-sku-monthly")}}" class="btn btn-info" >Monthly</a>
        <a href="{{url("admin/reports/sold-products-sku?date_from=" . $dayFrom7Pasts . "&date_to=" . date("Y-m-d"))}}" class="btn btn-info" >Weekly</a>
        <a href="{{ url("admin/reports/sold-products-sku?date_from=" . date("Y-m-d") . "&date_to=" . date("Y-m-d"))}}" class="btn btn-info" >Daily</a>
        <p class="mb-2"></p>


        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-20"></p>
                        <div class="row">

                            <?php

                                $allBranchesNames = $all_branches->pluck("branch_name")->all();
                                $allBranchesIds   =$all_branches->pluck("branch_id")->all();

                                if(count($all_branches)>1){
                                    $allBranchesNames   = array_merge(['All'], $all_branches->pluck("branch_name")->all());
                                    $allBranchesIds     = array_merge(['all'], $all_branches->pluck("branch_id")->all());
                                }

                                echo generate_select_tags_v2([
                                    "field_name"              => "branch_id",
                                    "label_name"              => "Branch Name",
                                    "text"                    => $allBranchesNames,
                                    "values"                  => $allBranchesIds,
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                if ($show_input_year==true){
                                     echo generate_select_years(
                                        $already_selected_value = "",
                                        $earliest_year = "2022",
                                        $class = "form-control",
                                        $name  = "selected_year",
                                        $label = "Select Year",
                                        $data = $request_data ?? "",
                                        $grid = "col-md-4"
                                    );
                                 }

                                if($show_from_date_and_to_date_inputs==true){
                                      $normal_tags = [
                                        "date_from", "date_to"
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

                                    echo generate_inputs_html_take_attrs($attrs);

                                }


                                 generateBTMSelect2([
                                     "field_name"              => "sku_id",
                                     "label_name"              => "Product Barcode",
                                     "data-placeholder"        => "Search by product barcode",
                                     "data-url"                => langUrl("admin/products-sku/get-product-sku-by-barcode?add_all=true"),
                                     "class"                   => "form-control convert_select_to_btm_select2",
                                     "hide_label"              => false,
                                     "grid"                    => "col-md-4",
                                     "data"                    => $request_data ?? "",
                                     "data-pre_selected_text"  => (isset($product_sku_obj) ? $product_sku_obj->product_name : ""),
                                     "data-pre_selected_value" => $request_data->sku_id ?? "",
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



                            ?>

                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date">Get Results</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="section-wrapper mb-3">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if($report_sold_products_buy->count()): ?>
                    <h6 class="slim-pagetitle mb-2">
                        Real Sold
                    </h6>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>Product name</span></th>
                                <th class="wd-15p"><span>Quantity</span></th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($report_sold_products_buy as $key => $item): ?>
                                <?php
                                    $returnQuantity = $report_sold_products_return->
                                    where("item_name", $item->item_name)->
                                    where("operation_type", 'return')->
                                    first();

                                    $quantity       = $item->order_quantity_sum - ($returnQuantity->order_quantity_sum ?? 0);

                                    $soldProductsChartData[$item->item_name] = $quantity;
                                ?>


                                <tr id="row">
                                    <td>
                                        {{$item->item_name}}
                                    </td>
                                    <td>
                                        {{$quantity}}
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div>


        </div>

        @if(isset($soldProductsChartData))
            <div class="section-wrapper mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <div id="yearly_sold_products_chart_id" data-value="{{json_encode($soldProductsChartData)}}" data-title="Yearly Sold Products" class="google_chart draw_bar_chart" style="height: 600px;"></div>
                    </div>
                </div>
            </div>
        @endif

        <div class="section-wrapper mb-3">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="row">
                <div class="col-6 col-md-6">
                    <h6 class="slim-pagetitle mb-2">
                        Total Sold
                    </h6>

                    @include('admin.subviews.reports.client_orders_reports.component.table_report',['report_sold_products'=>$report_sold_products_buy])
                </div>

                <div class="col-6 col-md-6">
                    <h6 class="slim-pagetitle mb-2">
                        Total Return
                    </h6>

                    @include('admin.subviews.reports.client_orders_reports.component.table_report',['report_sold_products'=>$report_sold_products_return])
                </div>
            </div>

        </div>

        @if(isset($report_sold_products_buy) && isset($report_sold_products_return))
            <div class="section-wrapper mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                            $totalSoldOnly = $report_sold_products_buy->pluck("order_quantity_sum", "item_name")->all();
                        ?>
                        <div id="total_sold_only_chart_id" data-value="{{json_encode($totalSoldOnly)}}" data-title="Total Sold Only" class="google_chart draw_bar_chart" style="height: 600px;"></div>
                    </div>
                    <div class="col-md-6">
                        <?php
                            $totalReturnOnly = $report_sold_products_return->pluck("order_quantity_sum", "item_name")->all();
                        ?>
                        <div id="total_return_only_chart_id" data-value="{{json_encode($totalReturnOnly)}}" data-title="Total Return Only" class="google_chart draw_bar_chart" style="height: 600px;"></div>
                    </div>
                </div>
            </div>
        @endif

    </div><!-- container -->
</div><!-- slim-mainpanel -->
