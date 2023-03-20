<?php

namespace App\models\employee;

use App\models\ModelUtilities;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class employee_task_comments_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "employee_task_comments";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'task_id', 'user_id', 'task_comment', 'comment_img', 'comment_file'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            employee_task_comments.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }


    public static function getCommentsByTaskId($taskId): Collection
    {

        $results = self::select(\DB::raw("
            employee_task_comments.*,
            users.full_name
        "));

        $results = $results
            ->join('users','users.user_id','=','employee_task_comments.user_id')
            ->where('employee_task_comments.task_id','=', $taskId)
            ->orderBy('employee_task_comments.created_at', 'desc');

        return ModelUtilities::general_attrs($results, []);

    }


}
