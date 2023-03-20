<div class="container mt-2">

    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">Copy & Translate Content</h6>
    </div><!-- slim-pageheader -->


    <div class="section-wrapper">

        <p class="mg-b-20 mg-sm-b-40"></p>

        <div class="table-wrapper">

            <div class="panel panel-info">
                <div class="panel-heading">Copy Data (single method)</div>
                <div class="panel-body">

                    <form class="row ajax_form" action="{{url("/admin/site-content/copy_from_lang_to_another")}}" method="post">

                        <?php
                            echo generate_select_tags_v2([
                                "field_name" => "method_name",
                                "label_name" => "Select Method",
                                "text"       => array_merge(["all"], $all_methods->pluck("method_name")->all()),
                                "values"     => array_merge(["all"], $all_methods->pluck("method_name")->all()),
                                "class"      => "form-control",
                                "grid"       => "col-md-4",
                            ]);

                            echo generate_select_tags_v2([
                                "field_name" => "from_lang",
                                "label_name" => "From lang",
                                "text"       => [config("default_language.main_lang_title")],
                                "values"     => [config("default_language.main_lang_title")],
                                "class"      => "form-control",
                                "grid"       => "col-md-4",
                            ]);

                            echo generate_select_tags_v2([
                                "field_name" => "to_lang",
                                "label_name" => "To lang",
                                "text"       => array_merge(["all"],$all_langs->pluck("lang_title")->all()),
                                "values"     => array_merge(["all"],$all_langs->pluck("lang_title")->all()),
                                "class"      => "form-control",
                                "grid"       => "col-md-4",
                            ]);
                        ?>

                        {!! csrf_field() !!}
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary confirm_before_go">Copy</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>


