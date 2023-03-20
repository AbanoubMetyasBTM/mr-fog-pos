<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admins</li>
            </ol>
            <h6 class="slim-pagetitle">Admins</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/admins","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/admins/save")}}"> Add New <i class="fa fa-plus"></i></a>
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
                            <th class="wd-15p"><span>Name</span></th>
                            <th class="wd-15p"><span>Permissions</span></th>
                            <th class="wd-15p"><span>Operations</span></th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
                            <?php foreach ($results as $key => $item): ?>
                                <tr id="row{{$item->user_id}}">
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        <img src="{{get_image_from_json_obj($item->logo_img_obj)}}" width="50" height="50">
                                    </td>
                                    <td>
                                        {{$item->full_name}}
                                    </td>

                                    <td>
                                        <?php if(havePermission("admin/permissions","manage_permissions")): ?>
                                            <a href="{{url("admin/admins/assign_permission/$item->user_id")}}" class="btn btn-info">
                                                Permissions
                                            </a>
                                        <?php endif; ?>
                                    </td>

                                    <td>

                                        <?php if(havePermission("admin/admins","edit_action")): ?>
                                            <a class="btn btn-primary mg-b-6" href="{{url("admin/admins/save/$item->user_id")}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if($key > 0 && havePermission("admin/admins","delete_action")): ?>
                                            <a href='#confirmModal'
                                               data-toggle="modal"
                                               data-effect="effect-super-scaled"
                                               class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                               data-tablename="{{\App\User::class}}"
                                               data-deleteurl="{{url("/general_remove_item")}}"
                                               data-itemid="{{$item->user_id}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
