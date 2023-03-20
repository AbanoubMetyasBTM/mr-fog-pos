<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gift Card Templates</li>
            </ol>
            <h6 class="slim-pagetitle">Gift Card Templates</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/gift_card_templates","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/gift-card-templates/save")}}">
                        Add New <i class="fa fa-plus"></i>
                    </a>
                </label>
            <?php endif; ?>

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-15p"><span>Template Title</span></th>
                            <th class="wd-15p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->template_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->template_title}}

                                </td>

                                <td>

                                    <?php if(havePermission("admin/gift_card_templates","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/gift-card-templates/save/$item->template_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/gift_card_templates","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\gift_card\gift_card_templates_m::class}}"
                                           data-deleteurl="{{url("/admin/gift-card-templates/delete")}}"
                                           data-itemid="{{$item->template_id}}">
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
