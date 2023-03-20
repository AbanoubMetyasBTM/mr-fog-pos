<?php
    $header_text        = "Add New Card";
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/gift-cards")}}">Gift Cards</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <form id="save_form" class="ajax_form disable_enter_submit" method="POST" action="{{url("admin/gift-cards/add-card")}}"  enctype="multipart/form-data">

            <div class="section-wrapper mg-y-20">
                <label class="section-title">Main Data</label>
                <p class="mg-b-20 mg-sm-b-20"></p>
                <div class="row ">
                    <?php

                        echo generate_select_tags_v2([
                            "field_name" => "card_template_id",
                            "label_name" => "Template Title",
                            "text"       => $templates->pluck("template_title")->all(),
                            "values"     => $templates->pluck("template_id")->all(),
                            "class"      => "form-control select2_search",
                            "grid"       => "col-md-4",
                        ]);

                        echo generate_select_tags_v2([
                            "field_name" => "branch_id",
                            "label_name" => "Branch Name",
                            "text"       => $branches->pluck("branch_name")->all(),
                            "values"     => $branches->pluck("branch_id")->all(),
                            "class"      => "form-control select2_search",
                            "grid"       => "col-md-4",
                        ]);

                        echo generateBTMSelect2([
                            "field_name"            => "client_id",
                            "label_name"            => "Select Client",
                            "data-placeholder"      => "Search by client name or phone",
                            "data-url"              => langUrl("admin/clients-orders/get-client-by-name-or-phone"),
                            "class"                  => "client_id form-control convert_select_to_btm_select2",
                            "hide_label"            => false,
                            "grid"                  => "col-md-4",
                            "required"              => "required",
                            "data-run_after_select" => "runAfterSelectClientId"
                        ]);

                    ?>
                </div>
            </div>

            <div class="hide_until_select_client">

                <div class="section-wrapper mg-y-20">
                    <label class="section-title">Gift Card Data</label>
                    <p class="mg-b-20 mg-sm-b-20"></p>
                    <div class="row">
                        <?php

                        $normal_fields = [
                            "card_title", "card_price"
                        ];

                        $attrs = generate_default_array_inputs_html(
                            $normal_fields,
                            $data               = "",
                            $key_in_all_fields  = "yes",
                            $requried           = "required",
                            $grid_default_value = 6
                        );

                        $attrs[0]['card_title']       = "Gift Card Title";
                        $attrs[3]['card_price']       = "number";
                        $attrs[2]['cash_paid_amount'] = "";
                        $attrs[3]['cash_paid_amount'] = "number";


                        echo generate_inputs_html_take_attrs($attrs);

                        $attrs = generate_default_array_inputs_html(
                            ["debit_card_paid_amount"],
                            $data               = "",
                            $key_in_all_fields  = "yes",
                            $requried           = "required",
                            $grid_default_value = 4
                        );
                        ?>
                    </div>
                </div>

                <div class="section-wrapper mg-y-10">
                    <label class="section-title">Payment Methods</label>
                    <p class="mg-b-20 mg-sm-b-20"></p>
                    <table class="table">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label>Available Amount In Wallet</label>
                                    <input type="text" id="available_amount_in_wallet" name="available_amount_in_wallet" class="form-control" readonly>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label>Client Wallet</label>
                                    <input type="number" id="client_wallet" class="form-control payment_method" name="wallet_paid_amount">
                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <label>Cash Paid Amount</label>
                                    <input type="number" class="form-control" name="cash_paid_amount">
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td class="col-md-4">
                                <label>Debit Card Paid Amount</label>
                                <input type="number" class="form-control" name="debit_card_paid_amount">
                            </td>
                            <td class="col-md-4">
                                <label>Credit Card Paid Amount</label>
                                <input type="number" class="form-control" name="credit_card_paid_amount">
                            </td>
                            <td class="col-md-4">
                                <label>Cheque Paid Amount</label>
                                <input type="number" class="form-control" name="cheque_paid_amount">
                            </td>
                        </tr>

                        <?php if (false): ?>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <label>Debit Card Receipt Image</label>
                                <input id="formFile" type="file" class="form-control" name="debit_card_receipt_img">
                            </td>
                            <td>
                                <label>Credit Card Receipt Image</label>
                                <input type="file" class="form-control" name="credit_card_receipt_img">
                            </td>
                            <td>
                                <label>Cheque Receipt Image</label>
                                <input type="file" class="form-control" name="cheque_card_receipt_img">
                            </td>
                        </tr>
                        <?php endif; ?>

                    </table>

                </div>

            </div>



            {{csrf_field()}}


            <div class="form-layout-footer mb-3">
                <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
            </div>

        </form>

    </div><!-- container -->
</div><!-- slim-mainpanel -->





