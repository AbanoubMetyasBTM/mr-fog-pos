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

            <?php if(havePermission("admin/my_hr_national_holidays", "add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/employee-hr/holiday-requests/save?type=$type")}}">
                        Add New Request<i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>
                        <div class="row">

                            <?php


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
                                    {{ $item->req_status }}
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
