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
                <li class="breadcrumb-item active" aria-current="page">National Holidays</li>
            </ol>
            <h6 class="slim-pagetitle">National Holidays</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper">

            <?php if(havePermission("admin/national_holidays","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/national-holidays/save")}}">
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
                            <th class="wd-10p"><span>#</span></th>
                            <th class="wd-10p"><span>Holiday Title</span></th>
                            <th class="wd-10p"><span>Country Name</span></th>
                            <th class="wd-10p"><span>Holiday Date</span></th>
                            <th class="wd-10p"><span>Created At</span></th>
                            <th class="wd-10p"><span>Actions</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->holiday_title}}
                                </td>
                                <td>
                                    {{$item->country_name}}
                                </td>
                                <td>
                                    {{$item->holiday_date}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    <?php if(havePermission("admin/national_holidays", "edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/national-holidays/save/$item->id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/national_holidays","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\hr\hr_national_holidays_m::class}}"
                                           data-deleteurl="{{url("/admin/national-holidays/delete")}}"
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
