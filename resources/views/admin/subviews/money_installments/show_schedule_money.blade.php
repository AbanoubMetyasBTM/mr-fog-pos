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
                <li class="breadcrumb-item"><a href="{{url("admin/suppliers")}}">Suppliers</a></li>

                <li class="breadcrumb-item active" aria-current="page">Money Installments Of- {{$wallet_owner_name}}</li>
            </ol>
            <h6 class="slim-pagetitle">Money Installments Of - {{$wallet_owner_name}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-3">

            <form id="save_form" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-12">


                        <p class="mg-b-20 mg-sm-b-40"></p>

                        <div class="row">

                            <?php

                                $normal_tags = [
                                    "installment_id", "date_from", "date_to"
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
                                    "field_name"              => "is_received",
                                    "label_name"              => "Installments Status",
                                    "text"                    => array_merge(["all"], ["received"], ["not_received"]),
                                    "values"                  => array_merge(["all"], ["1"], ["0"]),
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

                    <table id="datatable2" class="table display ">
                        <thead>
                            <tr>
                                <th class="wd-15p" style="text-align: center"><span>Installment Id</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Type</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Amount</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Installment date</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Is Received</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Receipt Image</span></th>
                                <th class="wd-15p" style="text-align: center"><span>Created at</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td style="text-align: center">{{$item->id}}</td>
                                <td style="text-align: center">{{$item->money_type}}</td>
                                <td style="text-align: center">
                                    {{$item->money_amount}}
                                </td>
                                <td style="text-align: center">
                                    {{$item->should_receive_payment_at}}
                                </td>

                                <td style="text-align: center">
                                    <?php
                                        echo $item->is_received == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?>
                                </td>
                                <td style="text-align: center">
                                    <?php
                                        if ($item->is_received == 1 && !is_null($item->img_obj)){

                                            $img = json_decode($item->img_obj, true);
                                            $img_url = asset($img['path']);
                                            echo "<a href='$img_url' class='btn btn-primary' target='_blank'>Show Image</a>";

                                        }
                                    ?>
                                </td>

                                <td style="text-align: center">
                                    {{$item->created_at}}
                                </td>
                                <td>

                                    <?php if(
                                        havePermission("admin/money_installments","receive_money_installment") &&
                                        $item->is_received == 0

                                        ): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/money-installments/receive-money-installment/" . $wallet_owner_name . "/". $wallet_id . "/" . $item->id)}}">
                                            Receive Installment
                                        </a>

                                    <?php endif; ?>
                                    <br>


                                    <?php if(
                                        havePermission("admin/money_installments","edit_schedule_money") &&
                                        $item->is_received == 0

                                        ): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/money-installments/edit-schedule-money/" . $wallet_owner_name . "/" . $wallet_id . "/" . $item->id)}}">
                                             <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(
                                        havePermission("admin/money_installments","delete_schedule_money") &&
                                        $item->is_received == 0
                                    ): ?>

                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\money_installments_m::class}}"
                                           data-deleteurl="{{url("/admin/money-installments/delete-schedule-money")}}"
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




