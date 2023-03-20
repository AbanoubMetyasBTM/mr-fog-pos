<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 * @var $branches \Illuminate\Support\Collection
 * @var $allEmployees \Illuminate\Support\Collection
 */
?>

<title>Requsts</title>
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>
                        <div class="row">

                            <?php

                                echo generate_select_tags_v2([
                                    "field_name"              => "branch_id",
                                    "label_name"              => "Branch Name",
                                    "text"                    => array_merge(["all"], $branches->pluck("branch_name")->all()),
                                    "values"                  => array_merge(["all"], $branches->pluck("branch_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "employee_id",
                                    "label_name"              => "Employee Name",
                                    "text"                    => array_merge(["all"], $allEmployees->pluck("full_name")->all()),
                                    "values"                  => array_merge(["all"], $allEmployees->pluck("user_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);


                                echo generate_select_tags_v2([
                                    "field_name"              => "req_status",
                                    "label_name"              => "Status",
                                    "text"                    => ["all", "accepted", "rejected", "waiting"],
                                    "values"                  => ["all", "accepted", "rejected", "waiting"],
                                    "class"                   => "form-control select2_primary",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);




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

                                if (is_object($request_data) && !isset($request_data->date_from)){
                                    $attrs[4]["date_from"] = \Carbon\Carbon::now()->startOfMonth();
                                }

                                 if (is_object($request_data) && !isset($request_data->date_to)){
                                    $attrs[4]["date_to"] = \Carbon\Carbon::now()->endOfMonth();
                                 }

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
                            <th class="wd-5p"><span>#</span></th>
                            <th class="wd-5p"><span>Employee Name</span></th>
                            <th class="wd-5p"><span>Branch Name</span></th>
                            <th class="wd-5p"><span>Title</span></th>
                            <th class="wd-5p"><span>Request Date</span></th>
                            <th class="wd-5p"><span>Description</span></th>
                            <th class="wd-5p"><span>Created At</span></th>
                            <th class="wd-5p"><span>Status</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->full_name}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->req_title}}
                                </td>
                                <td>
                                    {{$item->req_date}}
                                </td>
                                <td>
                                    {{$item->req_desc}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    <?php if(havePermission("admin/holiday_requests", "accept_action")): ?>

                                        <?php if ($item->req_status == "pending"): ?>
                                            <?php
                                                echo generate_multi_accepters(
                                                    $accepturl = url("/admin/holiday-requests/change-holiday-request-status"),
                                                    $item_obj = $item,
                                                    $item_primary_col = "id",
                                                    $accept_or_refuse_col = "req_status",
                                                    $model = App\models\hr\hr_holiday_requests_m::class,
                                                    $accepters_data = [
                                                        "pending"  => "Pending",
                                                        "rejected" => "Reject",
                                                        "accepted" => "Accept"
                                                    ]
                                                );
                                            ?>
                                        <?php else: ?>
                                            <label>{{capitalize_string($item->req_status)}}</label>
                                        <?php endif; ?>
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
