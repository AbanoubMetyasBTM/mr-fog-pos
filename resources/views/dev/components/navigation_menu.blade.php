<div class="slim-navbar">
    <div class="container">
        <ul class="nav">

            <li class="nav-item">
                <a class="nav-link do_not_ajax" href="{{url("admin/dashboard")}}">
                    <i class="icon ion-ios-home-outline"></i>
                    <span>Admin Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url("dev/dashboard")}}">
                    <i class="icon ion-ios-home-outline"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item with-sub">
                <a class="nav-link" href="#">
                    <i class="icon ion-paperclip"></i>
                    <span>Edit Content Generator</span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li>
                            <a href="{{url("/dev/generate_edit_content/show_all")}}">
                                 Show All Generators
                            </a>
                        </li>


                        <li>
                            <a href="{{url('/dev/generate_edit_content/save')}}">

                                Add New Generator
                            </a>
                        </li>

                        <li>
                            <a href="{{url("/dev/generate_edit_content/convert_db_to_files")}}">
                                 Convert from db to files
                            </a>
                        </li>

                        <li>
                            <a href="{{url("/dev/generate_edit_content/copy_from_lang_to_another")}}">
                                 Copy data
                            </a>
                        </li>

                    </ul>
                </div><!-- dropdown-menu -->
            </li>

            <li class="nav-item with-sub">
                <a class="nav-link" href="#">
                    <i class="icon ion-paperclip"></i>
                    <span>Site Permissions</span>
                </a>
                <div class="sub-item">
                    <ul>

                        <li>
                            <a href="{{url("dev/permissions/permissions_pages/show_all_permissions_pages")}}">
                                Show All Permission pages
                            </a>
                        </li>

                        <li>
                            <a href="{{url('dev/permissions/permissions_pages/save')}}">

                                Add New Permission Page
                            </a>
                        </li>

                        <li>
                            <a href="{{url('dev/permissions/permissions_pages/convert_db_to_files')}}">

                                Convert db to files
                            </a>
                        </li>


                        <li>
                            <a class="do_not_ajax" href="{{url('dev/permissions/assign_permission_for_this_user')}}">

                                Add All Permissions To Main User
                            </a>
                        </li>

                    </ul>
                </div><!-- dropdown-menu -->
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url("logout")}}">
                    <i class="fa fa-power-off"></i>
                    <span>Logout</span>
                </a>
            </li>



        </ul>
    </div><!-- container -->
</div><!-- slim-navbar -->
