<?php

    /**
     * @var $item_data object
     */

    $header_text        = $header_text . $wallet_owner_name;
    if (is_object($item_data)){
        $item_id = $item_data->wallet_id;
    }
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/money-installments/show-schedule-money/$wallet_owner_name/$item_data->wallet_id")}}">Schedule Money</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper">

            <p class="mg-b-10 mg-sm-b-20"></p>

            <div class="table-wrapper" style="display: flex; color: black">
                <div class="col-md-4 form-group">
                    <h4 class="big-text">Wallet Amount : {{$wallet_amount}}</h4>

                </div>
                <div class="col-md-4">
                    <h4 class="big-text">Scheduled Money : {{$scheduled_money}}</h4>
                </div>

                <div class="col-md-4">
                    <h4 class="big-text">Not Scheduled Money : {{$not_scheduled_money}}</h4>
                    <input hidden id="not_scheduled_money" value="{{$not_scheduled_money}}">
                </div>
            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->



        <div class="section-wrapper">
            <div id="money_will_paid_per_once_div">

            </div>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <form id="save_form" class="ajax_form" action="{{url("$post_url/$wallet_owner_name/$item_id")}}" method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <?php


                            if ($schedule_all_money == 'yes'){
                                $grid = 4;

                                $normal_fields = [
                                    "installments_num",
                                ];

                                $attrs = generate_default_array_inputs_html(
                                    $normal_fields,
                                    $data               = "",
                                    $key_in_all_fields  = "yes",
                                    $requried           = "required",
                                    $grid_default_value = $grid
                                );

                                $attrs[3]["installments_num"]  = "number";
                                echo generate_inputs_html_take_attrs($attrs);


                                echo generate_select_tags_v2([
                                    "field_name"              => "installments_type",
                                    "label_name"              => "Select Installments Type",
                                    "text"                    => ['Every Three Month', 'Every Two Month', 'Every Month', 'Every Two Week', 'Every Week'],
                                    "values"                  => ['three_month', 'two_month', 'one_month', 'two_week', 'week'],
                                    "class"                   => "form-control select2_primary",
                                    "data"                    => $request_data ?? "",
                                    "grid"                    => "col-md-$grid",
                                ]);

                            }
                            else{
                                $grid = 6;

                                $normal_fields = [
                                    "money_amount",
                                ];

                                $attrs = generate_default_array_inputs_html(
                                    $normal_fields,
                                    $data               = "",
                                    $key_in_all_fields  = "yes",
                                    $requried           = "required",
                                    $grid_default_value = $grid
                                );

                                $attrs[3]["money_amount"]  = "number";
                                echo generate_inputs_html_take_attrs($attrs);

                            }

                            $normal_fields = [
                                "should_receive_payment_at"
                            ];

                            $attrs = generate_default_array_inputs_html(
                                $normal_fields,
                                $data               = "",
                                $key_in_all_fields  = "yes",
                                $requried           = "required",
                                $grid_default_value = $grid
                            );

                            $attrs[3]["money_amount"] = "number";
                            $attrs[3]["should_receive_payment_at"] = "date";
                            $attrs[0]['should_receive_payment_at'] = "Installment Should Receive At";


                            echo generate_inputs_html_take_attrs($attrs);

                        ?>

                    </div>



                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>


                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->

<script>

    $(document).ready(function () {
        $('#installments_num_id').change(function() {



            if ($('#installments_num_id').val() !== '') {


                let msg = '';
                if ($('#not_scheduled_money').val() == 0){
                    msg = "<div id= 'alert_show_money_per_once' class='alert alert-danger' role='alert' style='font-size: 15px; font-weight: bold'>There is no unscheduled money to schedule</div>";
                }
                else {
                    let money_amount_per_once = $('#not_scheduled_money').val() / $('#installments_num_id').val();

                    money_amount_per_once = money_amount_per_once.toFixed(2);
                    msg = "<div id= 'alert_show_money_per_once' class='alert alert-primary' role='alert' style='font-size: 15px; font-weight: bold'>You will receive "  + money_amount_per_once +  " per installment</div>";
                }

                $('#alert_show_money_per_once').remove();
                $('#money_will_paid_per_once_div').append(msg)
            }

        });


        $('#money_amount_id').change(function() {


            if ($('#money_amount_id').val() !== '') {

                let msg = '';
                if ($('#not_scheduled_money').val() == 0){
                    msg = "<div id= 'alert_show_money_per_once' class='alert alert-danger' role='alert' style='font-size: 15px; font-weight: bold'>There is no unscheduled money to schedule</div>";
                }

                console.log(parseInt($('#money_amount_id').val()) > parseInt($('#not_scheduled_money').val()));




                if (parseFloat($('#money_amount_id').val()) > parseFloat($('#not_scheduled_money').val())){

                    let not_scheduled_money = $('#not_scheduled_money').val();

                    msg = "<div id= 'alert_show_money_per_once' class='alert alert-danger' role='alert' style='font-size: 15px; font-weight: bold'>Maximum amount to schedule " +  not_scheduled_money + "</div>";

                }

                $('#alert_show_money_per_once').remove();
                $('#money_will_paid_per_once_div').append(msg)
            }

        });
    });

</script>




