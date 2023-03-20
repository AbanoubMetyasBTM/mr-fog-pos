<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <a class="reload_by_ajax" href="{{url("admin/dashboard")}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->



        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">


                <form id="save_form" class="ajax_form" action="{{url("admin/paycheck/add-paycheck/$employee_id/$not_paid_weeks_indexes?year=$year&month=$month")}}" method="POST" enctype="multipart/form-data">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Total Work Hours</label>
                                    <input type="text" name="total_work_hours" class="form-control" value="{{$total_work_hours}}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Total Price</label>
                                    <input type="text" name="total_price" class="form-control" value="{{$total_price}}" readonly>
                                </div>
                            </div>

                            <?php

                                echo generate_select_tags_v2([
                                    "field_name" => "money_type",
                                    "label_name" => "Money Type",
                                    "text"       => ["Cash", "Debit Card", "Credit Card", "Cheque"],
                                    "values"     => ["cash", "debit_card", "credit_card", "cheque"],
                                    "class"      => "form-control",
                                    "hide_label" => false,
                                    "grid"       => "col-md-6",
                                    "required"   => "required",
                                ]);

                                echo generate_select_tags_v2([
                                    "field_name" => "is_received",
                                    "label_name" => "Money Is Received",
                                    "text"       => ["Yes", "No"],
                                    "values"     => ["1", "0"],
                                    "class"      => "form-control",
                                    "hide_label" => false,
                                    "grid"       => "col-md-6",
                                    "required"   => "required",
                                ]);
                            ?>

                        </div>

                        {{csrf_field()}}
                        <div class="form-layout-footer">
                            <input id="submit" type="submit" value="save" class="btn btn-primary bd-0">
                        </div>

                    </div>



                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->




