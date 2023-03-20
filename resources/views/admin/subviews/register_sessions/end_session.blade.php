<?php


    $header_text        = "Start Session";

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/registers")}}">Register Session</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <label class="section-title">Session End Data</label>

            <p class="mg-b-20 mg-sm-b-20"></p>
            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/register-sessions/end-session/$register_id")}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-wrapper mg-y-20">
                                <p class="mg-b-20 mg-sm-b-40"></p>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End Cash Amount</label>
                                            <input value="{{$data['register_end_cash_amount']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End Debit Amount</label>
                                            <input value="{{$data['register_end_debit_amount']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End Credit Amount</label>
                                            <input value="{{$data['register_end_credit_amount']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End cheque Amount</label>
                                            <input value="{{$data['register_end_cheque_amount']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End Debit Receipts Count</label>
                                            <input value="{{$data['register_end_debit_count']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End Credit Receipts Count</label>
                                            <input value="{{$data['register_end_credit_count']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Register End cheque Receipts Count</label>
                                            <input value="{{$data['register_end_cheque_count']}}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->




