<?php

    /**
     * @var $item_data object
     */

    $header_text = 'Edit Installment Money Of - ' . $wallet_owner_name;
    $url = 'admin/money-installments/show-schedule-money/' . $wallet_owner_name .'/' . $wallet_id;

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url($url)}}"> Money Installments Of {{$wallet_owner_name}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Installment</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper">
            <div id="money_will_paid_per_once_div">

            </div>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <form id="save_form" class="ajax_form" action="{{url("admin/money-installments/edit-schedule-money/$wallet_owner_name/$wallet_id/$item_data->id")}}" method="POST" enctype="multipart/form-data">

                    <?php


                        $normal_fields = [
                            "should_receive_payment_at"
                        ];

                        $attrs = generate_default_array_inputs_html(
                            $normal_fields,
                            $data               = "",
                            $key_in_all_fields  = "yes",
                            $requried           = "required",
                            $grid_default_value = 12
                        );


                        $attrs[0]['should_receive_payment_at'] = "Installment Should Receive At";
                        $attrs[3]["should_receive_payment_at"] = "date";
                        $attrs[4]["should_receive_payment_at"] = $item_data->should_receive_payment_at;


                        echo generate_inputs_html_take_attrs($attrs);

                    ?>

                    <br><br><br>




                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>


                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->





