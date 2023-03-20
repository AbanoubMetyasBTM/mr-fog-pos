<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{url("admin/employee-hr/employees-tasks")}}">My Tasks</a></li>
                <li class="breadcrumb-item active" aria-current="page">Employee Task</li>
            </ol>
            <h6 class="slim-pagetitle">My Task</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper mb-4">

            <div class="row mb-3">

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Employee Name : <span style="color: #868ba1; margin-left: 10px">{{$task->full_name}}</span>
                </div>

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Branch Name : <span style="color: #868ba1; margin-left: 10px">{{$task->branch_name}}</span>
                </div>

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Task ID : <span style="color: #868ba1; margin-left: 10px"># {{$task->task_id}}</span>
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Task Title : <span style="color: #868ba1; margin-left: 10px">{{$task->task_title}}</span>
                </div>

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Task Deadline : <span style="color: #868ba1; margin-left: 10px">{{$task->task_deadline}}</span>
                </div>

                <div class="col-md-4" style="font-size: 15px; font-weight: bold; color: black">
                    Status :
                     <span style="color: #868ba1; margin-left: 10px">
                         <?php if(($task->task_status == 'pending' || $task->task_status == 'in_progress') && havePermission("admin/my_hr_employees_tasks", "work_on_task")): ?>
                             <?php

                                 echo generate_multi_accepters(
                                     $accepturl = url("admin/employee-hr/employees-tasks/change-status-task"),
                                     $item_obj = $task,
                                     $item_primary_col = "task_id",
                                     $accept_or_refuse_col = "task_status",
                                     $model = 'App\models\employee\employee_tasks_m',
                                     $accepters_data = [
                                         "in_progress" => '<i>In Progress</i>',
                                         "done"        => '<i>Done</i>'
                                     ],
                                     $display_block = false,
                                     $func_after_edit = ""
                                 );

                             ?>
                         <?php else:?>
                            <label>{{capitalize_string($task->task_status)}}</label>
                        <?php endif; ?>

                    </span>
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-md-2" style="font-size: 15px; font-weight: bold; color: black">
                    Task Description :
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12">
                    <div style="border-radius: 5px; background-color: #f6f6f6;padding-left: 10px;padding-right: 10px;">
                        <br>
                        {!!  $task->task_desc !!}
                        <br>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-2" style="font-size: 15px; font-weight: bold; color: black">
                    Task Files :
                </div>
            </div>

            <?php
                $task->task_slider = json_decode($task->task_slider);
            ?>
            <ul>
                <?php foreach($task->task_slider->slider_objs as $key=>$item): ?>
                    <li>
                        <a target="_blank" href="{{get_image_from_json_obj($item)}}">
                            File {{$key+1}}
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>


        <h6 class="slim-pagetitle">Comments</h6>
        <br>
        <div class="section-wrapper mb-4">

            <div class="row mb-2  d-flex justify-content-center">
                <?php if(is_array($comments->all()) && count($comments->all())): ?>

                    <?php foreach($comments as $comment): ?>

                        <div id="row{{$comment->id}}" class="card mb-3 w-100" style="border-radius: 5px; background-color: #fafafa; box-shadow: 0 4px 8px 0 rgba(160,160,160,0.2), 0 6px 20px 0 rgba(159,159,159,0.19);">
                            <div class="card-body p-2">
                                <div class="ml-3 mt-1">
                                    <div class="row">

                                        <div class="col-md-8">
                                            <h6 class="fw-bold mb-1" style="font-size: 16px; color: black; font-weight: bold">{{$comment->full_name}}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="small" style="color: #1e41c3; position: absolute; right: 20px;"><i class="fa fa-clock-o"></i> {{$comment->created_at}}</h6>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="mt-2 col-md-10">
                                            {!!  $comment->task_comment !!}
                                            <?php if (!is_null($comment->comment_file)): ?>
                                                <a style="border-radius: 5px" href='{{get_image_from_json_obj($comment->comment_file)}}' class='btn btn-primary' target='_blank'>Show File</a>
                                            <?php endif; ?>

                                        </div>

                                        <div class="col-md-2">
                                            <?php if(havePermission("admin/my_hr_employees_tasks_comments","delete_action") && auth()->user()->user_id == $comment->user_id): ?>
                                                <a href='#confirmModal'
                                                   style="position: absolute; right: 30px; bottom: 2px; border-radius: 5px"
                                                   data-toggle="modal"
                                                   data-effect="effect-super-scaled"
                                                   class="btn btn-danger mg-b-3 modal-effect confirm_remove_item"
                                                   data-tablename="{{\App\models\employee\employee_task_comments_m::class}}"
                                                   data-deleteurl="{{url("/admin/employee-hr/employees-tasks-comments/delete")}}"
                                                   data-itemid="{{$comment->id}}">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endforeach ?>

                    <?php else : ?>

                    <a class="btn btn-warning btn-block mg-t-20">There's not Comments yet</a>


                <?php endif; ?>

            </div>

        </div>


        <div class="section-wrapper mb-4">

            <form class="ajax_form" action="{{url("admin/employee-hr/employees-tasks-comments/add")}}" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">

                        <div class="row">

                            <?php
                                $normal_tags = [
                                    "comment"
                                ];

                                $attrs                    = generate_default_array_inputs_html(
                                    $fields_name          = $normal_tags,
                                    $data                 = $request_data ?? "",
                                    $key_in_all_fields    = "yes",
                                    $required             = "required",
                                    $grid_default_value   = 12
                                );

                                $attrs[0]["comment"]    = "Add New Comment";
                                $attrs[3]["comment"]    = "textarea";
                                $attrs[5]["comment"]    .= " my_ckeditor";

                                echo
                                generate_inputs_html_take_attrs($attrs);

                            ?>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Upload File</label>
                                    <input type="file" id="comment_file_id" name="comment_file" class="form-control">
                                </div>
                            </div>

                            <input hidden name="task_id" value="{{$task->task_id}}">
                            {{csrf_field()}}

                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date add_comment">Send</button>
                            </div>

                        </div>

                    </div>
                </div>

            </form>

        </div>

    </div><!-- container -->
</div><!-- slim-mainpanel -->





