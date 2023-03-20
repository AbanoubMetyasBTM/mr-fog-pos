<?php
/**
 * @var boolean $show_input_year
 * @var boolean $show_from_date_and_to_date_inputs
 * @var \Illuminate\Support\Collection $total_clients_branch
 */
?>
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Clients</li>
            </ol>
            <h6 class="slim-pagetitle">Count Clients  {{$report_type_date}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-20"></p>
                        <div class="row">

                            <?php
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

                <?php if($total_clients_branch->count()): ?>
                    <h6 class="slim-pagetitle mb-2">
                      Client Orders
                    </h6>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>Branch Name</span></th>
                                <th class="wd-15p"><span>Total Count</span></th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($total_clients_branch as $key => $item): ?>

                            <?php
                              $soldProductsChartData[$item->item_name] = $item->total_count;

                            ?>


                                <tr id="row">
                                    <td>
                                        {{$item->item_name}}
                                    </td>
                                     <td>
                                        {{$item->total_count}}
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
                        <div id="total_orders_by_branch" data-value="{{json_encode($soldProductsChartData)}}" data-title="Total Client  {{$report_type_date}}" class="google_chart draw_bie_chart" style="height: 600px;"></div>
                    </div>
                </div>
            </div>
        @endif


    </div><!-- container -->
</div><!-- slim-mainpanel -->
