<div class="slim-mainpanel settings_page">
    <div class="container-fluid">

        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
            <h6 class="slim-pagetitle">Settings</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <form id="save_form" class="ajax_form" action="{{url("admin/settings")}}" method="POST" enctype="multipart/form-data">

                    <div class="section-wrapper mg-b-20">

                        <?php
                        /**
                         * @var $settings array
                         */
                        ?>

                        <div class="form-layout">
                            <div class="row mg-b-25">


                                <?php
                                    echo
                                    generate_select_tags_v2([
                                        "field_name"     => "default_timezone",
                                        "label_name"     => "default timezone",
                                        "text"           => \StaticData\TimeZones::$timezones_arr,
                                        "values"         => \StaticData\TimeZones::$timezones_arr,
                                        "selected_value" => [getSettingsValue("default_timezone")],
                                        "class"          => "form-control select2_search",
                                        "grid"           => "col-md-12",
                                    ]);

                                ?>


                            </div><!-- row -->


                        </div>


                    </div><!-- section-wrapper -->


                    {{csrf_field()}}

                    <div class="form-layout-footer">
                        <input id="submit" type="submit" name="_submit" value="Save" class="btn btn-primary bd-0">
                    </div>

                </form>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->

