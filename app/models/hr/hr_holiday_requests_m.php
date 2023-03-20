<?php

namespace App\models\hr;

use App\models\ModelUtilities;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class hr_holiday_requests_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "hr_holiday_requests";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'req_type', 'req_title',
        'req_desc', 'req_date', 'req_status'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            hr_holiday_requests.*
       "));

        return ModelUtilities::general_attrs($results, $attrs);
    }


    private static function getAllHolidayRequestsConds(array $attr = [])
    {
        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];
        $modelUtilitiesAttrs["whereIn"]    = [];


        // filters
        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attr["branch_id"] = $currentBranchId;
        }

        if (isset($attr["branch_id"]) && !empty($attr["branch_id"]) && $attr["branch_id"] != "all") {

            $employeesOfBranchIds = User::select('user_id')
                                            ->where('user_type','=','employee')
                                            ->where('branch_id','=', $attr["branch_id"])
                                            ->get()->toArray();

            $employeesOfBranchIds = collect($employeesOfBranchIds)->pluck('user_id');

            $modelUtilitiesAttrs["whereIn"] = ["employee_id" => $employeesOfBranchIds];
        }


        if (isset($attr["employee_id"]) && !empty($attr["employee_id"]) && $attr["employee_id"] != "all") {

            $modelUtilitiesAttrs["free_conds"][] = "
               hr_holiday_requests.employee_id = {$attr["employee_id"]}
            ";
        }


        if (isset($attr["req_type"]) && !empty($attr["req_type"])) {
            $modelUtilitiesAttrs["free_conds"][] = "
               hr_holiday_requests.req_type = '{$attr["req_type"]}'
            ";
        }

        if (isset($attr["req_status"]) && !empty($attr["req_status"]) && $attr["req_status"] !='all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               hr_holiday_requests.req_status = '{$attr["req_status"]}'
            ";
        }


        if (isset($attr["date_from"]) && !empty($attr["date_from"])) {

            $date = date('Y-m-d h:i:s', strtotime($attr["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               hr_holiday_requests.created_at >= '{$date}'
            ";
        }

        if (isset($attr["date_to"]) && !empty($attr["date_to"])) {

            $date = date('Y-m-d h:i:s', strtotime($attr["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               hr_holiday_requests.created_at <= '{$date}'
            ";
        }

        return $modelUtilitiesAttrs;
    }

    public static function getAllHolidayRequests(array $attrs = []): Collection
    {
        $results = self::select(\DB::raw("
            hr_holiday_requests.*,
            users.full_name,
            branches.branch_name
       "));

        $results = $results->join('users','users.user_id','=','hr_holiday_requests.employee_id')
                           ->join('branches','branches.branch_id','=','users.branch_id');

        return ModelUtilities::general_attrs($results, self::getAllHolidayRequestsConds($attrs));

    }


    public static function getAcceptedRequestsOfEmployeeInYear($employeeId, $requestType = null): Collection
    {
        $startOfYear   = Carbon::now()->startOfYear();
        $endOfYear     = Carbon::now()->endOfYear();

        $requests =  self::where('employee_id',$employeeId)->
            where('req_status','=', 'accepted')->
            where('created_at','>=', $startOfYear)->
            where('created_at','<=', $endOfYear);


        if (!is_null($requestType))
        {
            $requests = $requests->where('req_type', $requestType);
        }

        return $requests->get();
    }


    public static function updateStatusOfHolidayRequest($holidayRequestId, $status)
    {
        self::where('id', '=', $holidayRequestId)->
        update(array(
            "req_status" => $status,
        ));

    }


    public static function getHolidayRequestById($id)
    {
        return self::getData([
            "free_conds" => [
                "id = $id"
            ],
            "return_obj" => "yes"
        ]);

    }

}
