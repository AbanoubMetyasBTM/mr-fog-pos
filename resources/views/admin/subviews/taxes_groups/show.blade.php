<?php
/**
 * @var $results \Illuminate\Support\Collection
 **/
?>
<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Taxes Groups</li>
            </ol>
            <h6 class="slim-pagetitle">Taxes Groups</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <label class="section-title">
                <?php if(havePermission('admin/taxes_groups','add_action')): ?>
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/taxes-groups/save")}}">
                        Add New <i class="fa fa-plus"></i>
                    </a>
                <?php endif; ?>
            </label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">
                <table id="datatable1" class="table display ">
                    <thead>
                    <tr>
                        <th class="wd-15p"><span>#</span></th>
                        <th class="wd-15p"><span>Group Name</span></th>
                        <th class="wd-15p"><span>Actions</span></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $key => $item): ?>
                            <tr id="row{{$item->group_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->group_name}}
                                </td>
                                <td>

                                    <?php if(havePermission('admin/taxes_groups','edit_action')): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/taxes-groups/save/$item->group_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission('admin/taxes_groups','delete_action')): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\taxes_groups_m::class}}"
                                           data-deleteurl="{{url("/admin/taxes-groups/delete")}}"
                                           data-itemid="{{$item->group_id}}">
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
