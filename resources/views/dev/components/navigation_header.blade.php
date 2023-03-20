<div class="slim-header {{(config('menu_display') == "sidebar")?"with-sidebar":""}}">
    <div class="{{(config('menu_display') == "sidebar")?"container-fluid":"container"}}">
        <div class="slim-header-left">

            <h2 class="slim-logo">
                <a href="{{url("dev/dashboard")}}">
                    <span>
                        <img src="{{showContent("logo_and_icon.logo",true)}}-34,34">
                    </span>

                    <span class="logo_site_name">Dev Panel</span>
                </a>

            </h2>

            <?php if(config('menu_display') == "sidebar"): ?>
                <a href="" id="slimSidebarMenu" class="slim-sidebar-menu"><span></span></a>
            <?php endif; ?>

        </div><!-- slim-header-left -->
        <div class="slim-header-right">

            <div class="toggle-wrapper">
                <div class="switch_toggle toggle-light"></div>
            </div>

            <div class="dropdown dropdown-b">
                <a href="" class="header-notification seen" data-toggle="dropdown">
                    <i class="icon ion-ios-bell-outline"></i>

                    <?php if($count_notifications != 0): ?>
                        <span id="hide" class="indicator"></span>
                    <?php endif; ?>

                </a>
                <div class="dropdown-menu">
                    <div class="dropdown-menu-header">
                        <h6 class="dropdown-menu-title">الاشعارات</h6>

                    </div><!-- dropdown-menu-header -->
                    <div class="dropdown-list">
                        <!-- loop starts here -->

                        <div class="dropdown-activity-list">

                            <?php foreach ($notifications_header as $key => $item): ?>

                                <?php if($key == date("Y-m-d")): ?>
                                    <div class="activity-label">اليوم</div>
                                    <?php else: ?>
                                    <div class="activity-label">{{$key}}</div>
                                <?php endif; ?>

                                <?php foreach ($item as $not): ?>
                                    <div class="activity-item">
                                        <div class="row no-gutters">
                                            <?php if($not['not_priority'] == 'low'): ?>
                                                <div class="col-2 tx-center"><span class="square-10 bg-warning"></span></div>
                                                <?php elseif($not['not_priority'] == 'medium'): ?>
                                                    <div class="col-2 tx-center"><span class="square-10 bg-success"></span></div>
                                                    <?php elseif($not['not_priority'] == 'high'): ?>
                                                        <div class="col-2 tx-center"><span class="square-10 bg-danger"></span></div>
                                            <?php endif; ?>
                                                <div class="col-2 tx-right">{{$not->created_at->format('H:i:s')}}</div>
                                            <div class="col-8">{{$not->not_title}}</div>
                                        </div><!-- row -->
                                    </div><!-- activity-item -->

                                <?php endforeach; ?>

                        <?php endforeach; ?>

                        </div><!-- dropdown-activity-list -->

                        <div class="dropdown-list-footer">
                            <a href="{{url("dev/notifications/show_all/all")}}"><i class="fa fa-angle-down"></i> كل الاشعارات </a>
                        </div>
                    </div><!-- dropdown-list -->
                </div><!-- dropdown-menu-right -->
            </div><!-- dropdown -->


            <div class="dropdown dropdown-c">
                <a href="{{url("dev/dashboard")}}" class="logged-user" data-toggle="dropdown">
                    <img src="{{get_image_or_default($current_user->logo_path)}}" alt="">
                    <span>{{$current_user->full_name}}</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <nav class="nav">
                        <a href="{{url("vendor/admins/save/$current_user->user_id")}}" class="nav-link"><i class="icon ion-compose"></i> تعديل البيانات </a>
                        <a href="{{url("logout")}}" class="nav-link"><i class="icon ion-forward"></i> تسجيل خروج </a>
                    </nav>
                </div><!-- dropdown-menu -->
            </div><!-- dropdown -->

        </div><!-- header-right -->
    </div><!-- container -->
</div><!-- slim-header -->
