<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Languages</li>
            </ol>
            <h6 class="slim-pagetitle">Languages</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <label class="section-title">
                <?php if(havePermission('admin/languages','add_action')): ?>
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/langs/save")}}"> Add New <i class="fa fa-plus"></i></a>
                <?php endif; ?>
            </label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">
                <table id="datatable1" class="table display ">
                    <thead>
                    <tr>
                        <th class="wd-15p"><span>#</span></th>
                        <th class="wd-15p"><span>Image</span></th>
                        <th class="wd-20p"><span>Title</span></th>
                        <th class="wd-15p"><span>Actions</span></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($show_all_langs as $key => $item): ?>
                            <tr id="row{{$item->lang_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    <img src="{{get_image_from_json_obj($item->lang_img_obj)}}" width="50" height="50">
                                </td>
                                <td>
                                    {{$item->lang_text}}
                                </td>
                                <td>

                                    <?php if(havePermission('admin/languages','edit_action')): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/langs/save/$item->lang_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if($key > 0 && havePermission('admin/languages','delete_action')): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\langs_m::class}}"
                                           data-deleteurl="{{url("/general_remove_item")}}"
                                           data-itemid="{{$item->lang_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
