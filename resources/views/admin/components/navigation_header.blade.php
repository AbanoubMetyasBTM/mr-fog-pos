<div class="slim-header {{(config('menu_display') == "sidebar")?"with-sidebar":""}}">
    <div class="{{(config('menu_display') == "sidebar")?"container-fluid":"container"}}">
        <div class="slim-header-left">


            <?php if(config('menu_display') == "sidebar"): ?>
                <a href="" id="slimSidebarMenu" class="slim-sidebar-menu"><span></span></a>
            <?php endif; ?>

        </div><!-- slim-header-left -->
        <div class="slim-header-right">

            <div class="toggle-wrapper">
                <div class="switch_toggle toggle-light"></div>
            </div>

            <div class="dropdown dropdown-b">
                <a href="" class="header-notification seen do_not_ajax" data-toggle="dropdown">
                    <i class="icon ion-ios-bell-outline"></i>

                    <?php if($count_notifications != 0): ?>
                        <span id="hide" class="indicator"></span>
                    <?php endif; ?>

                </a>
                <div class="dropdown-menu">
                    <div class="dropdown-menu-header">
                        <h6 class="dropdown-menu-title">Notifications</h6>

                    </div><!-- dropdown-menu-header -->
                    <div class="dropdown-list">
                        <!-- loop starts here -->

                        <div class="dropdown-activity-list">

                            <?php foreach ($notifications_header as $key => $item): ?>

                                <?php if($key == date("Y-m-d")): ?>
                                    <div class="activity-label">Day</div>
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
                                            <div class="col-8 px-2">{{$not->not_title}}</div>
                                        </div><!-- row -->
                                    </div><!-- activity-item -->

                                <?php endforeach; ?>

                        <?php endforeach; ?>

                        </div><!-- dropdown-activity-list -->

                        <div class="dropdown-list-footer">
                            <a href="{{url("admin/notifications/show_all/all")}}"><i class="fa fa-angle-down"></i> All Notifications </a>
                        </div>
                    </div><!-- dropdown-list -->
                </div><!-- dropdown-menu-right -->
            </div><!-- dropdown -->

            <?php if(false): ?>
                <div class="dropdown dropdown-c">
                    <button href="#" class="logged-user no_button_css" data-toggle="dropdown" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{$admin_selected_lang_obj->lang_text}}
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <nav class="nav">

                            <?php foreach($all_langs as $key=>$lang): ?>
                                <a href="{{url("/admin/select-lang?selected_lang_id=$lang->lang_id")}}" class="nav-link do_not_ajax">
                                    {{$lang->lang_text}}
                                </a>
                            <?php endforeach; ?>

                        </nav>
                    </div><!-- dropdown-menu -->
                </div><!-- dropdown -->
            <?php endif; ?>


        </div><!-- header-right -->
    </div><!-- container -->
</div><!-- slim-header -->

