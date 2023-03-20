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
                <li class="breadcrumb-item active" aria-current="page">Gift Cards</li>
            </ol>
            <h6 class="slim-pagetitle">Gift Cards</h6>
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
                                    "label_name"              => "Added By",
                                    "text"                    => array_merge(["all"], $all_employees->pluck("full_name")->all()),
                                    "values"                  => array_merge(["all"], $all_employees->pluck("user_id")->all()),
                                    "class"                   => "form-control select2_search",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-4",
                                ]);


                                generateBTMSelect2([
                                    "field_name"            => "client_id",
                                    "label_name"            => "Select Client",
                                    "data-placeholder"      => "Search by client name or phone",
                                    "data-url"              => langUrl("admin/clients-orders/get-client-by-name-or-phone?show_all=true"),
                                    "class"                  => "client_id form-control convert_select_to_btm_select2",
                                    "hide_label"            => false,
                                    "grid"                  => "col-md-4",
                                    "required"              => "required",
                                    "data-allow_cache"      => "true",
                                    "data-pre_selected_text"  => isset($client_obj) ? $client_obj->client_name : "",
                                    "data-pre_selected_value" => $request_data->client_id ?? "",
                                ]);


                                $normal_tags = [
                                    "card_number", "date_from", "date_to"
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

            <?php if(havePermission("admin/gift_cards","add_action") && $current_user->user_role!="admin"): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/gift-cards/add-card")}}">
                        Add New Card <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-10p-force"><span>Card Id</span></th>
                                <th class="wd-10p-force"><span>Card Num</span></th>
                                <th class="wd-10p-force"><span>Branch</span></th>
                                <th class="wd-10p-force"><span>Added By</span></th>
                                <th class="wd-10p-force"><span>Client</span></th>
                                <th class="wd-10p-force"><span>Title</span></th>
                                <th class="wd-10p-force"><span>Price</span></th>
                                <th class="wd-10p-force"><span>Card Available Amount</span></th>
                                <th class="wd-10p-force"><span>Created At</span></th>
                                <th class="wd-10p-force"><span>Timezone</span></th>
                                <th class="wd-10p-force"><span>Exp Date</span></th>
                                <th class="wd-10p-force"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->card_id}}">
                                <td>
                                    {{$item->card_id}}
                                </td>
                                <td>
                                    {{$item->card_unique_number}}
                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->full_name}}
                                </td>
                                <td>
                                    {{$item->client_name}}
                                </td>
                                <td>
                                    {{$item->card_title}}
                                </td>
                                <td style="text-align: center">
                                    {{$item->card_price}}
                                    <b>
                                        {{$item->branch_currency}}
                                    </b>
                                </td>
                                <td style="text-align: center">
                                    <a href="{{url("/admin/transactions-log/show-log/Card-Wallet/$item->wallet_id")}}" target="_blank">
                                        {{$item->remained_amount}}
                                    </a>
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    {{$item->gift_card_timezone}}
                                </td>
                                <td>
                                    {{$item->card_expiration_date}}
                                </td>
                                <td>
                                    <?php if(havePermission("admin/gift_cards", "show_action")): ?>
                                        <a class="btn btn-warning mg-b-6" href="{{url("admin/gift-cards/show-card/$item->card_id")}}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/gift-cards/print-card/$item->card_id")}}">
                                            <i class="fa fa-print"></i>
                                        </a>

                                    <?php endif; ?>

                                    <?php if(havePermission("admin/transactions_log","show_log")): ?>

                                        <a class="btn btn-info mg-b-6" href="{{url("admin/transactions-log/show-log/$item->client_name-Gift Card/$item->wallet_id")}}">
                                            Show Transaction Log
                                        </a>
                                    <?php endif; ?>

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
