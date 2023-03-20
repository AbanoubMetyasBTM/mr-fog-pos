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
                <li class="breadcrumb-item active" aria-current="page">Transactions Log - {{$wallet_owner_name}}</li>
            </ol>
            <h6 class="slim-pagetitle">Transactions Log - {{$wallet_owner_name}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">


            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php

                                $normal_tags = [
                                    "transaction_id", "date_from", "date_to"
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "",
                                    $grid_default_value   = 3
                                );

                                if (is_object($request_data) && !isset($request_data->date_from)) {
                                    $attrs[4]["date_from"] = \Carbon\Carbon::now()->startOfMonth();
                                }

                                if (is_object($request_data) && !isset($request_data->date_to)) {
                                    $attrs[4]["date_to"] = \Carbon\Carbon::now()->endOfMonth();
                                }

                                $attrs[3]["date_from"]    = "date";
                                $attrs[3]["date_to"]      = "date";

                                echo
                                generate_inputs_html_take_attrs($attrs);


                                echo
                                generate_select_tags_v2([
                                    "field_name"              => "transaction_type",
                                    "label_name"              => "Transaction Type",
                                    "text"                    => array_merge(["all"],array_values(\App\models\transactions_log_m::$transactionTypes)),
                                    "values"                  => array_merge(["all"],array_keys(\App\models\transactions_log_m::$transactionTypes)),
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-3",
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

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display">
                        <thead>
                            <tr>
                                <th class="wd-15p"><span>log id</span></th>
                                <th class="wd-15p"><span>transaction Type</span></th>
                                <th class="wd-15p"><span>Operation Type</span></th>
                                <th class="wd-15p"><span>Amount</span></th>
                                <th class="wd-15p"><span>Note</span></th>
                                <th class="wd-15p"><span>Created at</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->t_log_id}}" class="{{$item->transaction_operation =="increase"?"table-success":"table-danger"}}">
                                <td>{{$item->t_log_id}}</td>
                                <td>{{\App\models\transactions_log_m::$transactionTypes[$item->transaction_type]??$item->transaction_type}}</td>
                                <td>{{$item->transaction_operation}}</td>
                                <td>
                                    {{$item->transaction_amount}}
                                    <b>
                                        {{$item->transaction_currency}}
                                    </b>
                                </td>
                                <td>
                                    {{$item->transaction_notes}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    <?php if(
                                        havePermission("admin/expenses","add_action") &&
                                        $item->is_refunded == 0 &&
                                        $item->transaction_type == 'expenses' &&
                                        $item->transaction_operation == 'decrease'
                                    ): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/expenses/refund/" .$item->t_log_id)}}">
                                            Refund <i class="fa fa-plus"></i>
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
