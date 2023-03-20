<?php

namespace App\models\employee;

use App\models\ModelUtilities;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class employee_warnings_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "employee_warnings";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'warning_desc', 'warning_img_obj', 'warning_is_received'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            employee_warnings.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }


    private static function getAllEmployeesWarningsConds($attr = [])
    {
        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];


        // filters
        if (isset($attr["branch_id"]) && !empty($attr["branch_id"]) && $attr["branch_id"] != "all") {

            $employeesOfBranchIds = User::getUsersTypeEmployeeByBranchId($attr["branch_id"]);

            $employeesOfBranchIds           = collect($employeesOfBranchIds)->pluck('user_id');
            $modelUtilitiesAttrs["whereIn"] = ["employee_id" => $employeesOfBranchIds];
        }


        if (isset($attr["employee_id"]) && !empty($attr["employee_id"]) && $attr["employee_id"] != "all") {

            $modelUtilitiesAttrs["free_conds"][] = "
               employee_warnings.employee_id = {$attr["employee_id"]}
            ";
        }

        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {
            $date = date('Y-m-d h:i:s', strtotime($attr["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_warnings.created_at > '{$date}'
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {
            $date = date('Y-m-d h:i:s', strtotime($attr["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               employee_warnings.created_at < '{$date}'
            ";
        }


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $modelUtilitiesAttrs["free_conds"][] = "
                branches.branch_id = $currentBranchId
            ";
        }

        return $modelUtilitiesAttrs;

    }


    public static function getAllEmployeesWarnings($attrs= [])
    {
        $results = self::select(\DB::raw("
            employee_warnings.*,
            branches.branch_name,
            users.full_name
        "));


        $results = $results->join('users','users.user_id','=','employee_warnings.employee_id')
            ->join('branches','branches.branch_id','=', 'users.branch_id');

        return ModelUtilities::general_attrs($results, self::getAllEmployeesWarningsConds($attrs));

    }


    public static function getEmployeeWarningById($warningId)
    {
        return self::getData([
            "free_conds" => [
                "id = $warningId"
            ],
            "return_obj"      => "yes",
        ]);
    }

}
