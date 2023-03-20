<?php

    /**
     * @var string $wallet_owner_name
     * @var object $item_data
     */

    $header_text    = "Withdraw Money From - ".$wallet_owner_name;

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/transactions-log/show-log/$wallet_owner_name/$item_data->wallet_id")}}">Transactions Log - {{$wallet_owner_name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">


            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <div class="alert alert-info">
                    <p class="m-0">
                        {{$wallet_owner_name}} has
                        <b style="color: blue;">{{$item_data->wallet_amount}} {{$currency_symbol}}</b>
                        at its wallet
                    </p>
                </div>

                <form id="save_form" class="ajax_form" action="{{url("admin/transactions-log/withdraw-money/$wallet_owner_name/$item_data->wallet_id")}}" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">

                                <label class="section-title">Basic Data</label>
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">

                                    <?php

                                        $normal_fields = [
                                            "money_amount", "admin_notes"
                                        ];

                                        $attrs = generate_default_array_inputs_html(
                                            $normal_fields,
                                            $data = "",
                                            $key_in_all_fields = "yes",
                                            $requried = "required",
                                            $grid_default_value = 6
                                        );

                                        $attrs[2]["money_amount"] .= " max='{$item_data->wallet_amount}'";

                                        $attrs[3]["money_amount"] = "number";
                                        $attrs[3]["admin_notes"]  = "textarea";

                                        echo generate_inputs_html_take_attrs($attrs);
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="Save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
