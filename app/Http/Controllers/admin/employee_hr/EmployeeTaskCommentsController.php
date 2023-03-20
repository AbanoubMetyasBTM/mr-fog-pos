<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\btm_form_helpers\image;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\employee\employee_task_comments_m;
use Illuminate\Http\Request;

class EmployeeTaskCommentsController extends AdminBaseController
{

    use CrudTrait;

    /** @var employee_task_comments_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Employees Tasks");

        $this->modelClass          = employee_task_comments_m::class;
        $this->routeSegment        = "employees-tasks";
        $this->primaryKey          = "id";
    }


    public function addNewComment(Request $request)
    {

        if (havePermission('admin/my_hr_employees_tasks_comments','add_action') === false){
            return [
                "error" => "You not have permission to add comments"
            ];
        }



        if (empty($request['comment'])){
            return [
                "error" => "Comment should not be empty"
            ];
        }
        // add new comment
        $data['user_id']      = auth()->user()->user_id;
        $data['task_id']      = $request['task_id'];
        $data['task_comment'] = $request['comment'];

        if (isset($request['comment_file'])) {

            $fileObj = image::general_save_img_without_attachment($request, ["img_file_name" => "comment_file",]);
            $data['comment_file'] = json_encode($fileObj);
        }

        employee_task_comments_m::create($data);

        return $this->returnMsgWithRedirection(
            $request,
            "admin/employee-hr/employees-tasks/show/" . $request['task_id'],
            ""
        );
    }

    public function delete(Request $request)
    {
        havePermissionOrRedirect('admin/my_hr_employees_tasks_comments', 'delete_action');

        $this->general_remove_item($request,$this->modelClass);
    }


}
