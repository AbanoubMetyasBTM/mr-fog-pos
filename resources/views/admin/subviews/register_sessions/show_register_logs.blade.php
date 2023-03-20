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
                <li class="breadcrumb-item"><a href="{{url("admin/register-sessions")}}">Registers Sessions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logs</li>
            </ol>
            <h6 class="slim-pagetitle">Registers Sessions Logs</h6>
        </div><!-- slim-pageheader -->

       <div class="section-wrapper mb-3">


            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php


                                echo generate_select_tags_v2([
                                    "field_name"              => "item_type",
                                    "label_name"              => "Item Type",
                                    "text"                    => array_merge(["All", "Order", "Gift Card"]),
                                    "values"                  => array_merge(["all", "order", "gift_card"]),
                                    "class"                   => "form-control",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name"              => "operation_type",
                                    "label_name"              => "Operation Type",
                                    "text"                    => array_merge(["All", "Buy", "Return"]),
                                    "values"                  => array_merge(["all", "buy", "return"]),
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
                                <th class="wd-10p-force"><span>Log Id</span></th>
                                <th class="wd-10p-force"><span>Session Id</span></th>
                                <th class="wd-10p-force"><span>Register Name</span></th>
                                <th class="wd-10p-force"><span>Item Type</span></th>
                                <th class="wd-10p-force"><span>Operation Type</span></th>
                                <th class="wd-10p-force"><span>Cash Amount</span></th>
                                <th class="wd-10p-force"><span>Debit Card Amount</span></th>
                                <th class="wd-10p-force"><span>Credit Card Amount</span></th>
                                <th class="wd-10p-force"><span>cheque Amount</span></th>
                                <th class="wd-10p-force"><span>Created At</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$item->log_id}}
                                </td>
                                <td>
                                    {{$item->register_session_id}}
                                </td>
                                <td>
                                    {{$item->register_name}}
                                </td>
                                <td>
                                    {{ capitalize_string($item->item_type) }}
                                </td>
                                <td>
                                    {{ capitalize_string($item->operation_type) }}
                                </td>
                                <td>
                                    {{$item->cash_paid_amount}}
                                </td>
                                <td>
                                    {{$item->debit_card_paid_amount}}
                                </td>
                                <td>
                                    {{$item->credit_card_paid_amount}}
                                </td>
                                <td>
                                    {{$item->cheque_paid_amount}}
                                </td>
                                <td>
                                    {{$item->created_at}}
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
