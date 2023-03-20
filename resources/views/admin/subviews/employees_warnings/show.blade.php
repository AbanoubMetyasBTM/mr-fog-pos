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
                <li class="breadcrumb-item active" aria-current="page">Employee Warnings</li>
            </ol>
            <h6 class="slim-pagetitle">Employee Warnings</h6>
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
                                    "grid"                    => "col-md-6",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "employee_id",
                                    "label_name"              => "Employee Name",
                                    "text"                    => array_merge(["all"], $allEmployees->pluck("full_name")->all()),
                                    "values"                  => array_merge(["all"], $allEmployees->pluck("user_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-6",
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

            <?php if(havePermission("admin/employees_warnings", "add_action")): ?>
            <label class="section-title">
                <a class="btn btn-primary mg-b-6" href="{{url("admin/employees-warnings/save")}}">
                    Add New <i class="fa fa-plus"></i>
                </a>
            </label>
            <?php endif; ?>


            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-5p"><span>#</span></th>
                            <th class="wd-5p"><span>Employee Name</span></th>
                            <th class="wd-5p"><span>Branch Name</span></th>
                            <th class="wd-5p"><span>Warning Description</span></th>
                            <th class="wd-5p"><span>Warning Image</span></th>
                            <th class="wd-5p"><span>Created At</span></th>
                            <th class="wd-5p"><span>Warning Is Received</span></th>
                            <th class="wd-5p"><span>Actions</span></th>
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
                                    {{$item->warning_desc}}
                                </td>
                                <td style="text-align: center">
                                    <a href="{{ get_image_from_json_obj($item->warning_img_obj) }}" class='btn btn-primary' target='_blank'>Show Image</a>
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>

                                <td style="text-align: center">
                                    <?php
                                        echo $item->warning_is_received == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?>
                                </td>

                                <td>
                                    <?php if(havePermission("admin/employees_warnings", "edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/employees-warnings/save/$item->id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                        <?php if(havePermission("admin/employees_warnings","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\employee\employee_warnings_m::class}}"
                                           data-deleteurl="{{url("/admin/employees-warnings/delete")}}"
                                           data-itemid="{{$item->id}}">
                                            <i class="fa fa-remove"></i>
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
