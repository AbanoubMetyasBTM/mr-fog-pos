<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
            </ol>
            <h6 class="slim-pagetitle">المستخدمين</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(is_array($results->all()) && count($results->all())): ?>

                    <table id="datatable2" class="table display ">
                        <thead>
                        <tr>
                            <th class="wd-15p"><span>#</span></th>
                            <th class="wd-15p"><span>الاسم</span></th>
                            <th class="wd-15p"><span>الهاتف</span></th>
                            <th class="wd-15p"><span>المsaveة</span></th>
                            <th class="wd-15p"><span>تم التفعيل؟</span></th>
                            <th class="wd-15p"><span>حاله العضويه ؟</span></th>
                            <th class="wd-15p"><span>عمليات</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $key => $item): ?>
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->full_name}}</td>
                                <td>{{$item->phone}}</td>
                                <td>
                                    {{$item->user_wallet}}
                                    <br>
                                    <a class="btn btn-primary mg-b-6" href="{{url("admin/users/wallet/logs/$item->user_id")}}">
                                        سجل العمليات
                                    </a>
                                </td>

                                <td>
                                    <?php
                                        echo generate_multi_accepters(
                                            $accepturl              = "",
                                            $item_obj               = $item,
                                            $item_primary_col       = "user_id",
                                            $accept_or_refuse_col   = "is_active",
                                            $model                  = \App\User::class,
                                            $accepters_data         =
                                            [
                                                "0"     => "<i class='fa fa-times'></i>",
                                                "1"     => "<i class='fa fa-check'></i>",
                                            ]
                                        );
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        echo generate_multi_accepters(
                                            $accepturl              = "",
                                            $item_obj               = $item,
                                            $item_primary_col       = "user_id",
                                            $accept_or_refuse_col   = "user_role",
                                            $model                  = \App\User::class,
                                            $accepters_data         =
                                            [
                                                "active"     => "فعاله",
                                                "under_check"     => "تحت الرقابه",
                                                "restricted"     => "مقيده",
                                                "suspended"     => "موقوفه",
                                            ]
                                        );

                                    ?>
                                </td>

                                <td>

                                    <a class="btn btn-primary mg-b-6" target="_blank" href="{{url("admin/chats/create-chat-with-user/$item->user_id")}}">
                                        محادثة
                                    </a>

                                    <a class="btn btn-primary mg-b-6" target="_blank" href="{{url("admin/users/send-notification/$item->user_id")}}">
                                        ارسال اشعار
                                    </a>

                                    <?php if(havePermission("admin/users", "edit_action")): ?>
                                        <a class="btn btn-primary mg-b-6" href="{{url("admin/users/save/$item->user_id")}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(havePermission("admin/users", "delete_action")): ?>
                                        <a href='#confirmModal'
                                           data-toggle="modal"
                                           data-effect="effect-super-scaled"
                                           class="btn btn-danger mg-b-6 modal-effect confirm_remove_item"
                                           data-tablename="App\User"
                                           data-deleteurl="{{url("/admin/user/delete")}}"
                                           data-itemid="{{$item->user_id}}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>

                                </td>


                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                    @include('global_components.pagination')

                <?php else : ?>

                    @include('global_components.no_results_found')

                <?php endif; ?>


            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
