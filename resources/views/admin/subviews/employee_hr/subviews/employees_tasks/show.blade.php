<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 * @var $branches \Illuminate\Support\Collection
 * @var $allEmployees \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Task</li>
            </ol>
            <h6 class="slim-pagetitle">My Task</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>
                        <div class="row">

                            <?php


                                echo generate_select_tags_v2([
                                    "field_name"              => "task_status",
                                    "label_name"              => "Status",
                                    "text"                    => ["All", "Pending", "In Progress", "Done", "Under Review", "Reviewed"],
                                    "values"                  => ["all", "pending", "in_progress", "done", "under_review", "reviewed",],
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
                            <th class="wd-5p"><span>Deadline</span></th>
                            <th class="wd-5p"><span>Status</span></th>
                            <th class="wd-5p"><span>Created At</span></th>
                            <th class="wd-5p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->task_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->task_title}}
                                </td>
                                <td>
                                    {{$item->task_deadline}}
                                </td>
                                <td>

                                    <?php if(($item->task_status == 'pending' || $item->task_status == 'in_progress') && havePermission("admin/my_hr_employees_tasks", "work_on_task")): ?>
                                        <?php

                                            echo generate_multi_accepters(
                                                $accepturl = url("admin/employee-hr/employees-tasks/change-status-task"),
                                                $item_obj = $item,
                                                $item_primary_col = "task_id",
                                                $accept_or_refuse_col = "task_status",
                                                $model = 'App\models\employee\employee_tasks_m',
                                                $accepters_data = [
                                                    "in_progress" => '<i>In Progress</i>',
                                                    "done"        => '<i>Done</i>'
                                                ],
                                                $display_block = false,
                                                $func_after_edit = ""
                                            );

                                        ?>
                                    <?php else:?>
                                        <label>{{capitalize_string($item->task_status)}}</label>
                                    <?php endif; ?>

                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>

                                <td>
                                    <?php if(havePermission("admin/my_hr_employees_tasks", "show_task")): ?>
                                    <a class="btn btn-warning mg-b-6" href="{{url("admin/employee-hr/employees-tasks/show/$item->task_id")}}">
                                        <i class="fa fa-eye"></i>
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
