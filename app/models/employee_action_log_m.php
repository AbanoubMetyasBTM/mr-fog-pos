<?php

namespace App\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class employee_action_log_m extends \Eloquent
{


    protected $table = "employee_action_log";

    protected $primaryKey = "id";

    public $timestamps=false;

    protected $fillable = [
        "user_id", "module", "action_url", "action_type",
        "old_obj", "request_headers", "request_body",
        "log_desc","logged_at",
    ];

    public static function createLog($data=[]){
       return self::create($data);
    }

    public static function getAllUsersActionLog($attrs){

        $attrs["branch_id"]   = Vsi($attrs["branch_id"] ?? "");
        $attrs["user_id"]     = Vsi($attrs["employee_id"] ?? "");
        $attrs['module']      = Vsi($attrs['module'] ?? "");
        $attrs['action_type'] = Vsi($attrs['action_type'] ?? "");
        $attrs['date_from']   = Vsi($attrs['date_from'] ?? "");
        $attrs['date_to']     = Vsi($attrs['date_to'] ?? "");
        $attrs['paginate']    = Vsi($attrs['paginate'] ?? 50);


        $result= self::select(\DB::raw("
            employee_action_log.*,
            users.full_name,
            branches.branch_name
        "))->
        join('users','users.user_id','=','employee_action_log.user_id')->
        leftJoin('branches','branches.branch_id','users.branch_id');

        if (!empty($attrs["branch_id"]) && $attrs["branch_id"] != 'all') {
            $result = $result->
            where('users.branch_id','=', $attrs["branch_id"]);
        }

        if (!empty($attrs['user_id']) && $attrs['user_id'] !='all'){
           $result = $result->
           where('employee_action_log.user_id', '=' , $attrs['user_id']);
        }

        if (!empty($attrs['module']) && $attrs['module'] !='all'){
            $result = $result->
            where('employee_action_log.module', '=' , $attrs['module']);
        }

        if (!empty($attrs['action_type']) && $attrs['action_type'] !='all'){
            $result = $result->
            where('employee_action_log.action_type', '=' , $attrs['action_type']);
        }

        if (!empty($attrs['date_from']) && !empty($attrs['date_to'])){

            $attrs['date_from']     = date('Y-m-d', strtotime($attrs['date_from']));
            $attrs['date_to']       = date('Y-m-d', strtotime($attrs['date_to']));

            $result = $result->
            whereBetween(DB::raw('DATE(employee_action_log.logged_at)'), [$attrs['date_from'],$attrs['date_to']]);
        }

        return $result->
        orderBy('employee_action_log.id','desc')->
        paginate($attrs['paginate']);
    }


}
