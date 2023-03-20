<?php
/**
 *
 * @var $results \Illuminate\Support\Collection
 * @var $registers_have_open_sessions
 * @var $registers_sessions \Illuminate\Support\Collection
 */
?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Registers</li>
            </ol>
            <h6 class="slim-pagetitle">Registers</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <?php if(havePermission("admin/registers","add_action")): ?>
                <label class="section-title">
                    <a class="btn btn-primary mg-b-6" href="{{url("admin/registers/save")}}">
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
                                <th class="wd-15p"><span>Register Name</span></th>
                                <th class="wd-15p"><span>Branch Name</span></th>
                                <th class="wd-15p"><span>Is Opened</span></th>
                                <th class="wd-15p"><span>Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>

                            <tr id="row{{$item->register_id}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$item->register_name}}

                                </td>
                                <td>
                                    {{$item->branch_name}}
                                </td>
                                <td>
                                    <?php if(havePermission("admin/registers_sessions","start_session") && !in_array($item->register_id, $registers_have_open_sessions)): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/register-sessions/start-session/$item->register_id")}}">
                                            Start Session
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/registers_sessions","end_session") && in_array($item->register_id, $registers_have_open_sessions) ): ?>
                                        <a class="btn btn-primary mg-b-6 mr-2" href="{{url("admin/register-sessions/add-change/$item->register_id")}}">
                                            Add Change
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/registers_sessions","end_session") && in_array($item->register_id, $registers_have_open_sessions) ): ?>
                                        <a class="btn btn-primary mg-b-6 mr-2" href="{{url("admin/register-sessions/end-session/$item->register_id")}}">
                                            End Session
                                        </a>

                                        <br>
                                        {{ $registers_sessions->where('register_id','=', $item->register_id)->first()->full_name }}
                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if(havePermission("admin/registers","edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/registers/save/$item->register_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>


                                    <?php if(havePermission("admin/registers","delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="{{\App\models\register\registers_m::class}}"
                                           data-deleteurl="{{url("/admin/registers/delete")}}"
                                           data-itemid="{{$item->register_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/registers_sessions", "show_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/register-sessions?register_id=$item->register_id")}}">
                                            Show Register Sessions
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
