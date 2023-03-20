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
                <li class="breadcrumb-item active" aria-current="page">Clients Orders</li>
            </ol>
            <h6 class="slim-pagetitle"></h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-20"></p>
                        <div class="row">

                            <?php

                                //DONE BY METS
                                generateBTMSelect2([
                                    "field_name"              => "client_id",
                                    "label_name"              => "Client Name",
                                    "data-placeholder"        => "Search by client name or phone",
                                    "data-url"                => langUrl("admin/clients-orders/get-client-by-name-or-phone?show_all=true"),
                                    "class"                   => "form-control convert_select_to_btm_select2",
                                    "hide_label"              => false,
                                    "grid"                    => "col-md-3",
                                    "data-pre_selected_text"  => isset($client_obj) ? $client_obj->client_name : "",
                                    "data-pre_selected_value" => $request_data->client_id ?? "",
                                ]);

                                $allBranches      = $branches;
                                $allBranchesNames = $allBranches->pluck("branch_name")->all();
                                $allBranchesIds   = $allBranches->pluck("branch_id")->all();

                                if(count($allBranches) > 1 ){
                                    $allBranchesNames = array_merge(['All'], $allBranches->pluck("branch_name")->all());
                                    $allBranchesIds    = array_merge(['all'], $allBranches->pluck("branch_id")->all());
                                }


                                echo generate_select_tags_v2([
                                    "field_name"              => "branch_id",
                                    "label_name"              => "Branch Name",
                                    "text"                    => $allBranchesNames,
                                    "values"                  => $allBranchesIds,
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-3",
                                ]);



                                echo generate_select_tags_v2([
                                    "field_name"              => "employee_id",
                                    "label_name"              => "Added By",
                                    "text"                    => array_merge(['All'], $all_employees->pluck("full_name")->all()),
                                    "values"                  => array_merge(['all'], $all_employees->pluck("user_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-3",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "order_status",
                                    "label_name"              => "Order Status",
                                    "text"                    => ["All", "Done", "Pick Up"],
                                    "values"                  => ["all", "done", "pick_up"],
                                    "class"                   => "form-control select2_primary",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-3",
                                ]);

                                $normal_tags = [
                                    "order_id", "date_from", "date_to"
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

            <?php if(havePermission("admin/clients_orders","add_action") && $current_user->user_role!="admin"): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/clients-orders/add-order")}}">
                        Add New Order
                        <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-5p-force"><span>Id</span></th>
                                <th class="wd-10p-force"><span>Client</span></th>
                                <th class="wd-10p-force"><span>Branch</span></th>
                                <th class="wd-10p-force"><span>Add By</span></th>
                                <th class="wd-10p-force"><span>Total Cost</span></th>
                                <th class="wd-10p-force"><span>Paid Amount</span></th>
                                <th class="wd-12p-force"><span>Returned Amount</span></th>
                                <th class="wd-13p-force"><span>Created At</span></th>
                                <th class="wd-13p-force"><span>Timezone</span></th>
                                <th class="wd-10p-force"><span>Status</span></th>
                                <th class="wd-10p-force"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->client_order_id}}">
                                <td>
                                    {{$item->client_order_id}}
                                </td>
                                <td>
                                    {{$item->client_name}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->emp_name}}
                                </td>
                                <td style="text-align: center">
                                    {{$item->total_cost}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td style="text-align: center">
                                    {{$item->total_paid_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td style="text-align: center">
                                    {{$item->total_return_amount}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    {{$item->order_timezone}}
                                </td>
                                <td>
                                    {{capitalize_string($item->order_status)}}
                                </td>

                                <td>
                                    <?php if(havePermission("admin/clients_orders", "show_action")): ?>
                                        <a class="btn btn-warning mg-b-6" href="{{url("admin/clients-orders/show-order/$item->client_order_id")}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/clients_orders", "show_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/clients-orders/print-order-invoice/$item->client_order_id")}}">
                                            Print Order Invoice
                                        </a>
                                    <?php endif; ?>

                                    <?php if($item->order_status == 'pick_up' &&havePermission("admin/clients_orders","add_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/clients-orders/make-order-done/$item->client_order_id")}}">
                                            Make order done
                                        </a>
                                    <?php else:?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/clients-orders/return-order/$item->client_order_id")}}">
                                            Return order
                                        </a>
                                    <?php endif; ?>




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
