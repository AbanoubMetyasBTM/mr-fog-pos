
<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 * @var $branches \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logs</li>
            </ol>
            <h6 class="slim-pagetitle">Employees actions</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-40"></p>
                        <div class="row">

                            <?php

                            $allBranchesNames = $branches->pluck("branch_name")->all();
                            $allBranchesIds   =$branches->pluck("branch_id")->all();

                            if(count($branches)>1){
                                $allBranchesNames   = array_merge(['All'], $branches->pluck("branch_name")->all());
                                $allBranchesIds     = array_merge(['all'], $branches->pluck("branch_id")->all());
                            }

                            echo generate_select_tags_v2([
                                "field_name"              => "branch_id",
                                "label_name"              => "Branch Name",
                                "text"                    => $allBranchesNames,
                                "values"                  => $allBranchesIds,
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


                                $normal_tags = [
                                    "date_from", "date_to",'action_type','module'
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 3
                                );

                                $attrs[3]["date_from"]    = "date";
                                $attrs[3]["date_to"]      = "date";

                                echo generate_inputs_html_take_attrs($attrs);

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
            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                <table id="datatable2" class="table display ">
                    <thead>
                    <tr>
                        <th class="wd-15p"><span>#</span></th>
                        <th class="wd-15p"><span>Branch Name</span></th>
                        <th class="wd-15p"><span>Full Name</span></th>
                        <th class="wd-15p"><span>Module</span></th>
                        <th class="wd-15p"><span>Action Type</span></th>
                        <th class="wd-15p"><span>Action Url</span></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $key => $item): ?>
                        <tr id="row{{$item->id}}">
                            <td>
                                {{$key+1}}
                            </td>
                            <td>
                                {{(is_null($item->branch_name)?'Admin':$item->branch_name)}}
                            </td>
                            <td>
                                {{$item->full_name}}
                            </td>
                            <td>
                                {{$item->module}}
                            </td>
                            <td>
                                {{$item->action_type}}
                            </td>
                            <td>
                                {{$item->action_url}}
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                    @include('global_components.pagination')

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->


