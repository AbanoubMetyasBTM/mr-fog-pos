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
                <li class="breadcrumb-item active" aria-current="page">Loyalty Points To Money</li>
            </ol>
            <h6 class="slim-pagetitle">Loyalty Points To Money</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/loyalty_points_to_money","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/loyalty_points_to_money/save")}}">
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
                                <th class="wd-15p"><span>Money Currency</span></th>
                                <th class="wd-15p"><span>Points Amount</span></th>
                                <th class="wd-15p"><span>Reward Money</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->money_currency}}</td>
                                <td>{{$item->points_amount}}</td>
                                <td>{{$item->reward_money}}</td>

                                <td>
                                    <?php if(havePermission("admin/loyalty_points_to_money","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/loyalty_points_to_money/save/$item->id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/loyalty_points_to_money","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\loyalty_points_to_money_m::class}}"
                                           data-deleteurl="{{url("/admin/loyalty_points_to_money/delete")}}"
                                           data-itemid="{{$item->id}}">
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
