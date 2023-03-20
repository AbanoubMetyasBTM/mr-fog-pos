<?php

namespace App\models\employee;

use App\models\ModelUtilities;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class employee_tasks_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "employee_tasks";

    protected $primaryKey = "task_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'task_title', 'task_desc',
        'task_deadline', 'task_status', 'task_slider'
    ];


    public static $taskStatus =
    [
        'pending'      => 'Pending',
        'done'         => 'Done',
        'under_review' => 'Under Review',
        'in_progress'  => 'In Progress',

    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            employee_tasks.*
        "));


        if(isset($attrs["need_users_join"])){
            $results = $results->addSelect(\DB::raw("
                users.full_name,
                branches.branch_name
            "))
            ->join("users", "users.user_id", "=", "employee_tasks.employee_id")
            ->join('branches','branches.branch_id','=','users.branch_id');
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }


    public static function getEmployeesTasks($attrs = [])
    {
        $results = self::select(\DB::raw("
            employee_tasks.*,
            users.full_name,
            branches.branch_name

        "));

        $results = $results->join('users','users.user_id','=','employee_tasks.employee_id')
                           ->join('branches','branches.branch_id','users.branch_id');

        return ModelUtilities::general_attrs($results, self::getEmployeesTasksConds($attrs));

    }


    private static function getEmployeesTasksConds($attrs =[])
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];

        // filters
        if (isset($attrs["branch_id"]) && !empty($attrs["branch_id"]) && $attrs["branch_id"] != "all") {
            $employeesOfBranchIds = User::getUsersTypeEmployeeByBranchId($attrs["branch_id"]);

            $employeesOfBranchIds           = collect($employeesOfBranchIds)->pluck('user_id');
            $modelUtilitiesAttrs["whereIn"] = ["employee_id" => $employeesOfBranchIds];
        }

        if (isset($attrs["employee_id"]) && !empty($attrs["employee_id"]) && $attrs["employee_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_tasks.employee_id = {$attrs["employee_id"]}
            ";
        }

        if (isset($attrs["task_status"]) && !empty($attrs["task_status"]) && $attrs["task_status"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_tasks.task_status = '{$attrs["task_status"]}'
            ";
        }

        if (isset($attrs["date_from"]) && !empty($attrs["date_from"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_tasks.created_at > '{$date}'
            ";
        }

        if (isset($attrs["date_to"]) && !empty($attrs["date_to"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_tasks.created_at < '{$date}'
            ";
        }


        if (isset($attrs["paginate"]) && !empty($attrs["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attrs["paginate"];
        }

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $modelUtilitiesAttrs["free_conds"][] = "
                branches.branch_id = $currentBranchId
            ";
        }

        return $modelUtilitiesAttrs;

    }


    public static function getEmployeeTaskById($taskId)
    {
        return self::getData([
            "free_conds" => [
                "task_id = $taskId"
            ],
            "return_obj"      => "yes",
            "need_users_join" => "yes"
        ]);
    }

    public static function getEmployeeTaskByIdAndEmployeeId($taskId, $employeeId)
    {
        return self::getData([
            "free_conds" => [
                "task_id = $taskId",
                "employee_id = $employeeId"
            ],
            "return_obj"      => "yes",
        ]);
    }


}
