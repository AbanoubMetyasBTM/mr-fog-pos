<?php

    $header_text    = " ارسل اشعار ";

?>


<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header_text}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header_text}}</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="<?=url("admin/users/send-notification/$user_id")?>" method="POST" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">العنوان</label>
                                <input type="text" name="not_title" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">المحتوي</label>
                                <textarea type="text" name="not_body" required class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" value="ارسل" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->



