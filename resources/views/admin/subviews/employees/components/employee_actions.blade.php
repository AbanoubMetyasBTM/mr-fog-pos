<div class="modal fade" id="employee_actions_modal_{{$item->employee_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{$item->full_name}} - Actions
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="slim-pageheader pt-0 pb-0">
                            <ol class="breadcrumb slim-breadcrumb">
                            </ol>
                            <h6 class="slim-pagetitle">
                                Actions
                            </h6>
                        </div>


                        <?php if(havePermission("admin/permissions","manage_permissions")): ?>
                            <a href="{{url("admin/admins/assign_permission/$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-lock"></i>
                                <span>Permissions</span>
                            </a>
                        <?php endif; ?>

                        <?php if(havePermission("admin/employees_tasks","show_action")): ?>
                            <a href="{{url("admin/employees-tasks?employee_id=$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-list-check"></i>
                                <span>Tasks</span>
                            </a>
                        <?php endif; ?>

                        <?php if(havePermission("admin/holiday_requests","show_action")): ?>
                            <a href="{{url("admin/holiday-requests/sick?employee_id=$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-virus"></i>
                                <span>Sick Requests</span>
                            </a>
                        <?php endif; ?>

                        <?php if(havePermission("admin/holiday_requests","show_action")): ?>
                            <a href="{{url("admin/holiday-requests/vacation?employee_id=$item->employee_id")}}" class="lp_link" title="Vacation Requests">
                                <i class="fa-solid fa-plane-departure"></i>
                                <span>Vacation Reqs</span>
                            </a>
                        <?php endif; ?>

                         <?php if(havePermission("admin/delay_early_requests","show_action")): ?>
                            <a href="{{url("admin/delay-early-requests/delay?employee_id=$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-hourglass-end"></i>
                                <span>Delay Requests</span>
                            </a>
                        <?php endif; ?>

                         <?php if(havePermission("admin/delay_early_requests","show_action")): ?>
                            <a href="{{url("admin/delay-early-requests/early?employee_id=$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-hourglass"></i>
                                <span>Early Requests</span>
                            </a>
                        <?php endif; ?>

                        <?php if(havePermission("admin/employee_login_logout","show_action")): ?>
                            <a href="{{url("admin/employee-login-logout/employee/$item->employee_id")}}" class="lp_link">
                                <i class="fa-solid fa-table"></i>
                                <span>Check In Check Out Table</span>
                            </a>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
