
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/gift-cards")}}">Gift Cards</a></li>
                <li class="breadcrumb-item active" aria-current="page">Show Gift Card #{{$card->card_id}}</li>
            </ol>
            <h6 class="slim-pagetitle">Main Data</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-5">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-4">
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Template Title</label>
                    <input value="{{$card->template_title}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Branch Name</label>
                    <input value="{{$card->branch_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Client Name</label>
                    <input value="{{$card->client_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Added By</label>
                    <input  class="form-control" value="{{$card->full_name}}" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Register Name</label>
                    <input value="{{$card->register_name}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Card Unique Number</label>
                    <input value="{{$card->card_unique_number}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Created At</label>
                    <input value="{{$card->created_at}}" class="form-control" readonly>
                </div>

                <div class="col-md-3 label_of_data_supplier_order">
                    <label>Expiration Date</label>
                    <input value="{{$card->card_expiration_date}}" class="form-control" readonly>
                </div>
            </div>


        </div>

        <div class="mb-4">
            <h6 class="slim-pagetitle">Card Data</h6>
        </div>
        <div class="section-wrapper mb-5">
            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="row mb-2">

                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Card Title</label>
                    <input value="{{$card->card_title}}" class="form-control" readonly>
                </div>

                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Gift Card Price</label>
                    <input value="{{$card->card_price}}" class="form-control" readonly>
                </div>


                <div class="col-md-4 label_of_data_supplier_order">
                    <label>Remained Amount</label>
                    <input value="{{$card->remained_amount}}" class="form-control" readonly>
                </div>

            </div>
        </div>


        <div class="mb-4">
            <h6 class="slim-pagetitle">Card Payment Methods</h6>
        </div>
        <div class="section-wrapper mb-5">

            <p class="mg-b-20 mg-sm-b-20"></p>
            <table class="table">
                <tr>
                    <td class="wd-25p-force">
                        <label>Cash Paid Amount</label>
                        <input value="{{$card->cash_paid_amount}}" class="form-control" readonly>
                    </td>
                    <td class="wd-25p-force">
                        <label>Debit Card Paid Amount</label>
                        <input value="{{$card->debit_card_paid_amount}}" class="form-control" readonly>
                    </td>
                    <td class="wd-25p-force">
                        <label>Credit Card Paid Amount</label>
                        <input value="{{$card->credit_card_paid_amount}}" class="form-control" readonly>
                    </td>
                    <td class="wd-25p-force">
                        <label>Cheque Paid Amount</label>
                        <input value="{{$card->cheque_paid_amount}}" class="form-control" readonly>
                    </td>
                </tr>
                <?php if(false): ?>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <label>Debit Card Receipt Image</label>

                            <?php if((!is_null($card->debit_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($card->debit_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label>Credit Card Receipt Image</label>
                            <?php if((!is_null($card->credit_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($card->credit_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label>Cheque Receipt Image</label>
                            <?php if((!is_null($card->cheque_card_receipt_img_obj))): ?>
                                <a href='{{get_image_from_json_obj($card->cheque_card_receipt_img_obj)}}' class='btn btn-primary form-control'
                                   target='_blank' style='border-radius: 5px'>
                                    Show Image
                                </a>
                            <?php else:?>
                                <a href='#' class='btn btn-primary form-control'
                                   style='border-radius: 5px'>
                                    No Found Image
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>





    </div><!-- container -->
</div><!-- slim-mainpanel -->

