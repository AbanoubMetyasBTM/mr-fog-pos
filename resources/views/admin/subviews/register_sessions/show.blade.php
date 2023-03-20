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
                <li class="breadcrumb-item"><a href="{{url("admin/registers")}}">Registers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Registers Sessions</li>
            </ol>
            <h6 class="slim-pagetitle">Registers Sessions</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper mb-3">

            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php



                                echo generate_select_tags_v2([
                                    "field_name"              => "register_id",
                                    "label_name"              => "Register Name",
                                    "text"                    => array_merge(["All"], collect($registers)->pluck('register_name')->all()),
                                    "values"                  => array_merge(["all"], collect($registers)->pluck('register_id')->all()),
                                    "class"                   => "form-control",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);





                                $normal_tags = [
                                    "session_id", "date_from", "date_to"
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

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-5p-force"><span>Id</span></th>
                                <th class="wd-5p-force"><span>Reg Name</span></th>
                                <th class="wd-5p-force"><span>Emp Name</span></th>
                                <th class="wd-5p-force"><span>Start At</span></th>
                                <th class="wd-5p-force"><span>Start Cash Amount</span></th>
                                <th class="wd-5p-force"><span>End Cash Amount</span></th>
                                <th class="wd-5p-force"><span>End Debit Amount</span></th>
                                <th class="wd-5p-force"><span>Debit Count</span></th>
                                <th class="wd-5p-force"><span>End Credit Amount</span></th>
                                <th class="wd-5p-force"><span>Credit Count</span></th>
                                <th class="wd-5p-force"><span>End Cheque Amount</span></th>
                                <th class="wd-5p-force"><span>Cheque Count</span></th>
                                <th class="wd-5p-force"><span>Closed At</span></th>
                                <th class="wd-5p-force"><span>Approved</span></th>
                                <th class="wd-5p-force"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$item->id}}
                                </td>
                                <td>
                                    {{$item->register_name}}

                                </td>
                                <td>
                                    {{$item->employee_name}}
                                </td>

                                <td>
                                    {{$item->register_start_at}}
                                </td>
                                <td>
                                    {{$item->register_start_cash_amount}}
                                </td>
                                <td>
                                    {{$item->register_end_cash_amount}}
                                </td>
                                <td>
                                    {{$item->register_end_debit_amount}}
                                </td>
                                <td>
                                    {{$item->register_end_debit_count}}
                                </td>
                                <td>
                                    {{$item->register_end_credit_amount}}
                                </td>
                                <td>
                                    {{$item->register_end_credit_count}}
                                </td>
                                <td>
                                    {{$item->register_end_cheque_amount}}
                                </td>
                                <td>
                                    {{$item->register_end_cheque_count}}
                                </td>
                                <td>
                                    {{$item->register_closed_at}}
                                </td>

                                <td style="text-align: center">
                                    <?php
                                        echo $item->approved_by_admin == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?>
                                </td>

                                <td>

                                    <?php if(havePermission("admin/registers_sessions_logs", "show_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/register-sessions-logs/show-register-session-logs?session_id=$item->id")}}">
                                            Show Register Sessions Logs
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
