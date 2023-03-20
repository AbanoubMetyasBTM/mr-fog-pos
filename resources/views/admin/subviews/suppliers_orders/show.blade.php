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
                <li class="breadcrumb-item active" aria-current="page">Suppliers Orders</li>
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

                                echo generate_select_tags_v2([
                                    "field_name"              => "sup_id",
                                    "label_name"              => "Supplier Name",
                                    "text"                    => array_merge(["all"], $suppliers->pluck("sup_name")->all()),
                                    "values"                  => array_merge(["all"], $suppliers->pluck("sup_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "branch_id",
                                    "label_name"              => "Branch Name",
                                    "text"                    => array_merge(["all"], ["system"], $branches->pluck("branch_name")->all()),
                                    "values"                  => array_merge(["all"], ["null"], $branches->pluck("branch_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);


                                echo generate_select_tags_v2([
                                    "field_name"              => "employee_id",
                                    "label_name"              => "Employee Name",
                                    "text"                    => array_merge(["all"], $all_employees->pluck("full_name")->all()),
                                    "values"                  => array_merge(["all"], $all_employees->pluck("user_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "order_status",
                                    "label_name"              => "Order Status",
                                    "text"                    => ["all", "done", "pending"],
                                    "values"                  => ["all", "done", "pending"],
                                    "class"                   => "form-control select2_primary",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);



                                $normal_tags = [
                                    "order_id", "original_order_id", "date_from", "date_to"
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 4
                                );

                                if (is_object($request_data) && !isset($request_data->date_from)) {
                                    $attrs[4]["date_from"] = \Carbon\Carbon::now()->startOfMonth();
                                }

                                if (is_object($request_data) && !isset($request_data->date_to)) {
                                    $attrs[4]["date_to"] = \Carbon\Carbon::now()->endOfMonth();
                                }

                                $attrs[3]["date_from"]         = "date";
                                $attrs[3]["date_to"]           = "date";
                                $attrs[0]["original_order_id"] = "Invoice Id";

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

            <?php if(havePermission("admin/suppliers_orders","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/suppliers-orders/add-order")}}">
                        Add New Order <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-5p-force"><span>Order Id</span></th>
                                <th class="wd-5p-force"><span>Invoice Id</span></th>
                                <th class="wd-5p-force"><span>Supplier</span></th>
                                <th class="wd-5p-force"><span>Branch</span></th>
                                <th class="wd-5p-force"><span>Employee</span></th>
                                <th class="wd-5p-force"><span>Total Cost</span></th>
                                <th class="wd-5p-force"><span>Paid</span></th>
                                <th class="wd-5p-force"><span>Additional Fees</span></th>
                                <th class="wd-5p-force"><span>Ordered At</span></th>
                                <th class="wd-5p-force"><span>Created At</span></th>
                                <th class="wd-5p-force"><span>Status</span></th>
                                <th class="wd-5p-force"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->supplier_order_id}}">
                                <td>
                                    {{$item->supplier_order_id}}
                                </td>
                                <td>
                                    {{$item->original_order_id}}
                                </td>
                                <td>
                                    {{$item->sup_name}}
                                </td>
                                <td>
                                    <?php if ($item->branch_name == null): ?>
                                        ------
                                    <?php else: ?>
                                        {{$item->branch_name}}
                                    <?php endif; ?>
                                </td>
                                <td>
                                    {{$item->emp_name}}
                                </td>
                                <td>
                                    {{$item->total_cost}}
                                </td>
                                <td>
                                    {{$item->paid_amount}}
                                </td>
                                <td>
                                    {{$item->additional_fees}}
                                </td>
                                <td>
                                    {{$item->ordered_at}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    {{$item->order_status}}
                                </td>

                                <td>
                                    <?php if(havePermission("admin/suppliers_orders", "show_order")): ?>
                                        <a class="btn btn-warning mg-b-6" href="{{url("admin/suppliers-orders/show-order/$item->supplier_order_id")}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if($item->order_status=='pending'): ?>
                                        <a class="btn btn-info mg-b-6"
                                           href="{{url("admin/suppliers-orders/make-order-done/$item->supplier_order_id")}}">
                                            make order done
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
