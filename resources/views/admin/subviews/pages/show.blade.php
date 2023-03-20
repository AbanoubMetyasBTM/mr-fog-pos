<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pages</li>
            </ol>
            <h6 class="slim-pagetitle">Pages</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/pages","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/site-pages/save")}}"> Add New <i class="fa fa-plus"></i></a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-15p"><span>Image</span></th>
                            <th class="wd-15p"><span>Page Title</span></th>
                            <th class="wd-15p"><span>Hide?</span></th>
                            <th class="wd-15p"><span>Order</span></th>
                            <th class="wd-15p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
                        <?php foreach ($results as $key => $item): ?>
                            <tr id="row{{$item->page_id}}" data-fieldname="page_order" data-itemid="<?= $item->page_id ?>" data-tablename="{{\App\models\pages_m::class}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    <img src="{{get_image_from_json_obj($item->page_img_obj)}}" width="50" height="50">
                                </td>
                                <td>
                                    <a href="{{langUrl("pages/$item->page_slug")}}">
                                        {{$item->page_title}}
                                    </a>
                                </td>

                                <td>
                                    <?php
                                        echo generate_multi_accepters(
                                            $accepturl              = "",
                                            $item_obj               = $item,
                                            $item_primary_col       = "page_id",
                                            $accept_or_refuse_col   = "hide_page",
                                            $model                  = 'App\models\pages_m',
                                            $accepters_data         =
                                            [
                                                "1"     => "<i class='fa fa-times'></i>",
                                                "0"     => "<i class='fa fa-check'></i>",
                                            ]
                                        );
                                    ?>
                                </td>

                                <td>
                                    {{$item->page_order + 1}}
                                </td>

                                <td>

                                    <?php if(havePermission("admin/pages","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/site-pages/save/$item->page_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/pages","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="App\models\pages_m"
                                           data-deleteurl="{{url("/admin/site-pages/delete")}}"
                                           data-itemid="{{$item->page_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    @include("global_components.order_btn_action")

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
