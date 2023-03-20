<?php

    /**
     * @var $item_data object
     */

    $header_text = 'Receive Money Installment Of - ' . $wallet_owner_name;
    $url = 'admin/money-installments/show-schedule-money/'.$wallet_owner_name.'/'. $wallet_id;

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url($url)}}">Money Installments Of {{$wallet_owner_name}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Receive Installment</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-10 mg-sm-b-20"></p>

            <div class="table-wrapper" style="display: flex; color: black">
                <div class="col-md-4 form-group">
                    <h4 class="big-text">Money Amount: {{$item_data->money_amount}}</h4>

                </div>
                <div class="col-md-4">
                    <h4 class="big-text">Installment Money Type : {{$item_data->money_type}}</h4>
                </div>

                <div class="col-md-4">
                    <h4 class="big-text">Installment Date : {{$item_data->should_receive_payment_at}}</h4>
                </div>
            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->


        <div class="section-wrapper">
            <div id="money_will_paid_per_once_div">

            </div>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <form id="save_form" class="ajax_form" action="{{url("admin/money-installments/receive-money-installment/$wallet_owner_name/$wallet_id/$item_data->id")}}" method="POST" enctype="multipart/form-data">

                    <?php
                        $attrs['field_name']  = 'img_obj';
                        $attrs['field_label'] = 'Money Installment Receipt';

                        echo generate_img_tags_for_form_v2($attrs)
                    ?>

                    <br>

                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>


                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->





