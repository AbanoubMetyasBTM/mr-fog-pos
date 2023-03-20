<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit ({{$user_obj->full_name}}) Permissions</li>
            </ol>
            <h6 class="slim-pagetitle">
                Edit Permissions -
                ({{$user_obj->full_name}})
            </h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">

            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="table-wrapper">

                <?php if(havePermission("admin/permissions", "assign_all_permission")): ?>
                    <a class="btn btn-primary mg-b-6 ask_before_go" href="{{url("admin/admins/assign-all-permission/$user_obj->user_id")}}">
                        Assign All Permissions
                    </a>
                <?php endif; ?>

                <form id="save_form" class="ajax_form" action="<?=url("admin/admins/assign_permission/$user_obj->user_id")?>" method="POST" enctype="multipart/form-data">


                    <table class="table table-striped table-bordered">

                        <thead>
                        <tr>
                            <th>Permission</th>
                            <th>Show</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Other Permissions</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $acceptersColumns = [
                                    "show_action",
                                    "add_action",
                                    "edit_action",
                                    "delete_action",
                                ];
                            ?>
                            <?php foreach($all_user_permissions as $user_per_key=>$user_per_val): ?>
                                <?php
                                    if (!isset($all_permission_pages[$user_per_key])) {
                                        continue;
                                    }
                                ?>

                                <tr>
                                    <th>
                                        <p title="{{$all_permission_pages[$user_per_key]->page_name}}" style="min-width: 100px;word-break: break-all;">
                                            <?php
                                                $page_name_for_site_content = $all_permission_pages[$user_per_key]->page_name;
                                                $page_name_for_site_content = str_replace("admin/","",$page_name_for_site_content);
                                                $page_name_for_site_content = string_safe($page_name_for_site_content);
                                            ?>

                                            {{showContent("permission_translate.{$page_name_for_site_content}")}}
                                        </p>
                                    </th>

                                    <?php if (!$all_permission_pages[$user_per_key]->hide_accept_buttons): ?>
                                        <?php foreach($acceptersColumns as $key=>$column): ?>
                                            <th>
                                                <?php
                                                    echo generate_multi_accepters(
                                                        $accepturl = url("/admin/admins/permissions-multi-accepters"),
                                                        $item_obj = $user_per_val,
                                                        $item_primary_col = "per_id",
                                                        $accept_or_refuse_col = $column,
                                                        $model = 'App\models\permissions\permissions_m',
                                                        $accepters_data = [
                                                            "0" => '<i class="fa fa-times"></i>',
                                                            "1" => '<i class="fa fa-check"></i>'
                                                        ]
                                                    );
                                                ?>
                                            </th>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    <?php endif; ?>



                                    <th>

                                        <?php
                                            $all_additional_permissions_text = [];
                                            $all_additional_permissions = $all_permission_pages[$user_per_key]->all_additional_permissions;

                                            if (!isset_and_array($all_additional_permissions)) {
                                                continue;
                                            }

                                            $selected_additional_permissions = json_decode($user_per_val->additional_permissions);
                                            if (!isset_and_array($selected_additional_permissions)) {
                                                $selected_additional_permissions = [];
                                            }

                                            foreach ($all_additional_permissions as $permission){
                                                $all_additional_permissions_text[] = showContent("permission_translate.{$permission}");
                                            }

                                            echo generate_radio_btns(
                                                $input_type = "checkbox",
                                                $field_name = "additional_perms_new" . $user_per_val->per_id . "[]",
                                                $label_name = "Other Permissions",
                                                $text = $all_additional_permissions,
                                                $values = $all_additional_permissions,
                                                $selected_value = $selected_additional_permissions,
                                                $class = "",
                                                $data = "",
                                                $grid = "col-md-12",
                                                $hide_label = false,
                                                $additional_data = "",
                                                $custom_style = ""
                                            );

                                        ?>

                                    </th>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>


                    {{csrf_field()}}
                    <input type="submit" value="save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
                </form>


            </div>
        </div>
    </div>
</div>


