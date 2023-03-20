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
                <li class="breadcrumb-item active" aria-current="page">Coupons</li>
            </ol>
            <h6 class="slim-pagetitle">Coupons</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/coupons","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/coupons/save")}}">
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
                            <th class="wd-10p-force"><span>#</span></th>
                            <th class="wd-10p-force"><span>Title</span></th>
                            <th class="wd-10p-force"><span>Code</span></th>
                            <th class="wd-10p-force"><span>Branch Name</span></th>
                            <th class="wd-10p-force"><span>Type</span></th>
                            <th class="wd-10p-force"><span>Value</span></th>
                            <th class="wd-10p-force"><span>Date Range</span></th>
                            <th class="wd-10p-force"><span>Limited No</span></th>
                            <th class="wd-10p-force"><span>Used Times</span></th>
                            <th class="wd-10p-force"><span>Status</span></th>
                            <th class="wd-10p-force"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->coupon_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->coupon_title}}
                                </td>
                                <td>
                                    {{$item->coupon_code}}
                                </td>
                                <td>
                                    {{$item->branch_id == 0 ? "All Branches" :$item->branch_name}}
                                </td>
                                <td>
                                    {{$item->coupon_code_type}}
                                </td>
                                <td>
                                    {{$item->coupon_code_value}}
                                </td>
                                <td>
                                    {{date("Y-m-d", strtotime($item->coupon_start_date))}}
                                     :
                                    {{date("Y-m-d", strtotime($item->coupon_end_date))}}
                                </td>

                                <td style="text-align: center">
                                    {{$item->coupon_limited_number}}
                                </td>
                                <td style="text-align: center">
                                    {{$item->coupon_used_times}}
                                </td>
                                <td style="text-align: center">
                                    <?php
                                    echo $item->coupon_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                    ?></td>

                                <td>
                                    <?php if(havePermission("admin/coupons","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/coupons/save/$item->coupon_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/coupons","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\coupon\coupons_m::class}}"
                                           data-deleteurl="{{url("/admin/coupons/delete")}}"
                                           data-itemid="{{$item->coupon_id}}">
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
