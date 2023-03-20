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
                <li class="breadcrumb-item active" aria-current="page">Employees</li>
            </ol>
            <h6 class="slim-pagetitle">Employees</h6>
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
                                    "text"                    => array_merge(["all"], $all_employees->pluck("full_name")->all()),
                                    "values"                  => array_merge(["all"], $all_employees->pluck("user_id")->all()),
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

            <?php if(havePermission("admin/employees", "add_action")): ?>
            <label class="section-title">
                <a class="btn btn-primary mg-b-6" href="{{url("admin/employees/save")}}">
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
                            <th class="wd-10p"><span>#</span></th>
                            <th class="wd-10p"><span>Employee Name</span></th>
                            <th class="wd-10p"><span>Role</span></th>
                            <th class="wd-10p"><span>Branch Name</span></th>
                            <th class="wd-10p"><span>Email</span></th>
                            <th class="wd-10p"><span>Phone</span></th>
                            <th class="wd-10p"><span>Salary</span></th>
                            <th class="wd-10p"><span>Created At</span></th>
                            <th class="wd-10p"><span>Is Active</span></th>
                            <th class="wd-10p"><span>Actions</span></th>
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
                                    {{capitalize_string($item->user_role)}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->email}}
                                </td>
                                <td>
                                   {{$item->phone_code }}   {{$item->phone}}
                                </td>
                                <td>
                                    {{$item->hour_price }}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>

                                <td>
                                    {{$item->created_at}}
                                </td>

                                <td style="text-align: center">
                                    <?php
                                        echo $item->is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?>
                                </td>

                                <td>

                                    @include("admin.subviews.employees.components.employee_actions")

                                    <button type="button" class="btn btn-primary mg-b-6" data-toggle="modal" data-target="#employee_actions_modal_{{$item->employee_id}}">
                                        Actions
                                    </button>

                                    <?php if(havePermission("admin/employees","show_action")): ?>

                                        <a class="btn btn-warning mg-b-6 modal-effect show_data"
                                            data-toggle="modal"
                                            data-effect="effect-super-scaled"
                                            data-item="{{$item}}"
                                            data-url="{{url("/admin/employees/show-employee-data")}}"
                                            data-itemid="{{$item->id}}">
                                            <i class="fa fa-eye" style="color: white"></i>
                                        </a>

                                    <?php endif; ?>

                                    <?php if(havePermission("admin/employees", "edit_action")): ?>
                                        <?php
                                        if (
                                            $current_user->user_role=="admin" ||
                                            ($current_user->user_role=="branch_admin" &&  $current_user->user_id != $item->user_id)
                                        ):
                                        ?>
                                            <a class="btn btn-primary mg-b-6" href="{{url("admin/employees/save/$item->id")}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/employees","delete_action")): ?>
                                        <?php
                                        if (
                                            $current_user->user_role=="admin" ||
                                            ($current_user->user_role=="branch_admin" &&  $current_user->user_id != $item->user_id)
                                        ):
                                        ?>
                                            <a href='#confirmModal'
                                               data-toggle="modal"
                                               data-effect="effect-super-scaled"
                                               class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                               data-tablename="{{\App\models\employee\employee_details_m::class}}"
                                               data-deleteurl="{{url("/admin/employees/delete")}}"
                                               data-itemid="{{$item->id}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    {!! $results->links() !!}
                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->


<script>







</script>
