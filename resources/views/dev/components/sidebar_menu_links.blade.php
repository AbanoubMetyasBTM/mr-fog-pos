<div class="slim-sidebar">
    <label class="sidebar-label"></label>

    <ul class="nav nav-sidebar">


        <li class="sidebar-nav-item parent_nav_item">
            <a class="sidebar-nav-link do_not_ajax" href="{{url("dev/dashboard")}}">
                <i class="fa fa-power-off"></i>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li class="sidebar-nav-item parent_nav_item">
            <a class="sidebar-nav-link nav-toggle" href="javascript:;">
                <i class="fa fa-users"></i>
                <span class="title">Edit Content Generator</span>
                <span class="arrow"></span>
            </a>
            <ul class="nav sidebar-nav-sub">


                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url("/dev/generate_edit_content/show_all")}}">
                        <i class="fa fa-link"></i> Show All Generators
                    </a>
                </li>


                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url('/dev/generate_edit_content/save')}}">
                        <i class="fa fa-link"></i>
                        Add New Generator
                    </a>
                </li>

                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url("/dev/generate_edit_content/convert_db_to_files")}}">
                        <i class="fa fa-link"></i> Convert from db to files
                    </a>
                </li>

                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url("/dev/generate_edit_content/copy_from_lang_to_another")}}">
                        <i class="fa fa-link"></i> Copy data
                    </a>
                </li>
            </ul>
        </li>

        <li class="sidebar-nav-item parent_nav_item">
            <a class="sidebar-nav-link nav-toggle" href="javascript:;">
                <i class="fa fa-users"></i>
                <span class="title">Site Permissions</span>
                <span class="arrow"></span>
            </a>
            <ul class="nav sidebar-nav-sub">
                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url("dev/permissions/permissions_pages/show_all_permissions_pages")}}">
                        <i class="fa fa-link"></i> Show All Permission pages
                    </a>
                </li>

                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url('dev/permissions/permissions_pages/save')}}">
                        <i class="fa fa-link"></i>
                        Add New Permission Page
                    </a>
                </li>

                <li class="nav-item">
                    <a class="sidebar-nav-link" href="{{url('dev/permissions/permissions_pages/convert_db_to_files')}}">
                        <i class="fa fa-link"></i>
                        Convert db to files
                    </a>
                </li>


                <li class="nav-item">
                    <a class="sidebar-nav-link do_not_ajax" href="{{url('dev/permissions/assign_permission_for_this_user')}}">
                        <i class="fa fa-link"></i>
                        Edit Main User Permissions
                    </a>
                </li>

            </ul>
        </li>



        <li class="sidebar-nav-item parent_nav_item">
            <a class="sidebar-nav-link" href="{{url("logout")}}">
                <i class="fa fa-power-off"></i>
                <span class="title">Logout</span>
            </a>
        </li>




    </ul>
</div><!-- slim-sidebar -->
