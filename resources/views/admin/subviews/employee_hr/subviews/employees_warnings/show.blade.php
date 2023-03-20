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
                <li class="breadcrumb-item active" aria-current="page">My Warnings</li>
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

                                $normal_tags = [
                                    "date_from", "date_to"
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 6
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
                            <th class="wd-5p"><span>Warning Description</span></th>
                            <th class="wd-5p"><span>Warning Image</span></th>
                            <th class="wd-5p"><span>Created At</span></th>
                            <th class="wd-5p"><span>Warning Is Received</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
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
