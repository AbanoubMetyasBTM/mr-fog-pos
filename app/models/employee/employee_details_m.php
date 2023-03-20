<?php

namespace App\models\employee;

use App\models\ModelUtilities;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class employee_details_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "employee_details";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'employee_working_days', 'employee_required_working_hours_per_day',
        'employee_should_start_work_at', 'employee_should_end_work_at', 'create_order_pin_number',
        'hour_price', 'employee_overtime_hour_rate', 'employee_vacation_hour_rate',
        'employee_sick_vacation_max_requests', 'employee_delay_requests_max_requests',
        'employee_early_requests_max_requests', 'employee_vacation_max_requests'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            employee_details.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }


    public static function getEmployees($attrs = [], $id = null)
    {
        $results = self::select(\DB::raw("
            employee_details.*,
            users.user_id,
            users.branch_id,
            users.email,
            users.user_role,
            users.full_name,
            users.phone,
            users.phone_code,
            users.is_active,
            users.logo_img_obj,
            branches.branch_name,
            branches.branch_id
        "));

        $results = $results->join('users','users.user_id','=','employee_details.employee_id')
                           ->join('branches','branches.branch_id','users.branch_id');

        if ($id != null) {
            $results             = $results->where('employee_details.id', '=', $id);
            $attrs["return_obj"] = "yes";
        }

        return ModelUtilities::general_attrs($results, self::getEmployeesConds($attrs));

    }


    public static function getEmployeesOrOrSpecificBranch($attrs = [], $id = null)
    {
        $results = self::select(\DB::raw("
            employee_details.*,
            users.user_id,
            users.email,
            users.user_role,
            users.full_name,
            users.phone,
            users.phone_code,
            users.is_active,
            users.logo_img_obj,
            branches.branch_name,
            branches.branch_id,
            branches.branch_currency

        "));

        $results = $results->join('users','users.user_id','=','employee_details.employee_id')
            ->join('branches','branches.branch_id','users.branch_id');

        if ($id != null) {
            $results             = $results->where('employee_details.id', '=', $id);
            $attrs["return_obj"] = "yes";
        }


        return ModelUtilities::general_attrs($results, self::getEmployeesConds($attrs));

    }

    private static function getEmployeesConds($attr =[])
    {

        $modelUtilitiesAttrs               = $attr;
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];

        // filters
        if (isset($attr["branch_id"]) && !empty($attr["branch_id"]) && $attr["branch_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
                branches.branch_id = {$attr["branch_id"]}
            ";
        }

        if (isset($attr["user_role"]) && !empty($attr["user_role"]) ) {
            $modelUtilitiesAttrs["free_conds"][] = "
                users.user_role = '{$attr["user_role"]}'
            ";
        }


        if (isset($attr["employee_id"]) && !empty($attr["employee_id"]) && $attr["employee_id"] != "all") {
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_details.employee_id = {$attr["employee_id"]}
            ";
        }

        if (isset($attr["paginate"]) && !empty($attr["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attr["paginate"];
        }

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $modelUtilitiesAttrs["free_conds"][] = "
                branches.branch_id = $currentBranchId
            ";
        }


        return $modelUtilitiesAttrs;

    }


    public static function getEmployeeDataByEmployeeId($employeeId)
    {

        $results = self::select(\DB::raw("
            employee_details.*,
            users.*,
            branches.*
        "));

        $results = $results->
        join('users','users.user_id','=','employee_details.employee_id')->
        join('branches','branches.branch_id','users.branch_id')->
        where('employee_details.employee_id','=', $employeeId);

        $attr["return_obj"] =  "yes";

        return ModelUtilities::general_attrs($results, $attr);
    }



}
