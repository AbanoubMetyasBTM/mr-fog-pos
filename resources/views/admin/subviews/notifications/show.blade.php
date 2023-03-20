

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="manager-header">
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                </ol>
                <h6 class="slim-pagetitle">Notifications</h6>
            </div><!-- slim-pageheader -->
            <a id="managerNavicon" href="" class="contact-navicon"><i class="icon ion-navicon-round"></i></a>
        </div><!-- manager-header -->

        <div class="manager-wrapper">
            <div class="manager-right">

                <?php if(is_array($all_notifications) && count($all_notifications)): ?>

                <?php foreach ($all_notifications as $key => $item): ?>
                <div class="file-group">

                    <?php if($key == date("Y-m-d")): ?>
                    <label class="section-label">Current Day</label>
                    <?php else: ?>
                    <label class="section-label">{{$key}}</label>
                    <?php endif; ?>

                    <?php foreach ($item as $not): ?>

                    <div id="row{{$not->not_id}}" class="file-item">
                        <div class="row no-gutters wd-100p">
                            <div class="col-12 col-sm-7 d-flex align-items-center">
                                <?php if($not['not_priority'] == 'low'): ?>
                                <div class="col-2 tx-center">
                                    <span class="square-10 bg-warning"></span>
                                </div>
                                <?php elseif($not['not_priority'] == 'medium'): ?>
                                <div class="col-2 tx-center">
                                    <span class="square-10 bg-success"></span>
                                </div>
                                <?php elseif($not['not_priority'] == 'high'): ?>
                                <div class="col-2 tx-center">
                                    <span class="square-10 bg-danger"></span>
                                </div>
                                <?php endif; ?>

                                <?php if($not->not_type == "request_link" && $not->target_id > 0): ?>
                                <a href="{{url("admin/requests/links?id=$not->target_id")}}">
                                    {{$not->not_title}}
                                </a>
                                <?php else : ?>
                                {{$not->not_title}}
                                <?php endif; ?>

                            </div><!-- col-6 -->
                            <div class="col-6 col-sm-4 mg-t-5 mg-sm-t-0 badge badge-light"
                                 title="{{\Carbon\Carbon::createFromTimestamp(strtotime($not->created_at))->diffForHumans()}}">
                                <i class="fa fa-clock-o"></i>
                                {{$not->created_at}}
                            </div> &emsp;

                            <a href='#confirmModal'
                               data-toggle="modal"
                               data-effect="effect-super-scaled"
                               class="mg-b-6 modal-effect confirm_remove_item"
                               data-tablename="App\models\notification_m"
                               data-deleteurl="{{url("admin/notifications/delete")}}"
                               data-itemid="{{$not->not_id}}">
                                <i class="fa fa-remove delete-notification"></i>
                            </a>
                        </div><!-- row -->
                    </div><!-- file-item -->

                    <?php endforeach; ?>

                </div><!-- file-group --> <br>

                <?php endforeach; ?>

                @include('global_components.pagination')

                <?php else : ?>

                @include('global_components.no_results_found')

                <?php endif; ?>

            </div><!-- manager-right -->
            <div class="manager-left">

                <nav class="nav">
                    <a href="{{url('admin/notifications/show_all/all')}}" class="nav-link">
                        <span>All Notifications</span>
                        <span class="badge badge-secondary">{{$total_notifications}}</span>
                    </a>
                </nav>

            </div><!-- manager-left -->
        </div><!-- manager-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->

