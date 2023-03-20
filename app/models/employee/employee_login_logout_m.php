<?php

namespace App\models\employee;

use App\models\ModelUtilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class employee_login_logout_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "employee_login_logout";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'work_date', 'login_logout_times', 'late_time_hours',
        'early_leave_hours', 'should_work_hours', 'working_hours', 'remain_hours',
        'overtime_hours', 'is_work_day', 'work_day_is_general_holiday',
        'work_day_is_demanded_holiday', 'work_day_has_early_leave',
        'work_day_has_delay_request'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            employee_login_logout.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getLoginLogoutTblOfCurrentMonthByEmployeeId($employeeId, $attrs = [])
    {


        if (isset($attrs['date']) && !empty($attrs['date'])){
            $dateFrom = date('Y-m-01', strtotime($attrs['date']));
            $dateTo   = date('Y-m-t', strtotime($attrs['date']));
        }
        else{
            $dateFrom = date('Y-m-01');
            $dateTo   = date('Y-m-t');
        }

        $dateFrom = Vsi($dateFrom);
        $dateTo   = Vsi($dateTo);


        return self::getData([
            "free_conds" => [
                "employee_login_logout.employee_id = {$employeeId}",
                "work_date >= '{$dateFrom}'",
                "work_date <= '{$dateTo}'",
            ],
        ]);
    }

    public static function getCurrentDayLoginLogoutTblByEmployeeId($employeeId)
    {
        $currentDay = Carbon::now()->format('Y-m-d');


        return self::getData([
            "free_conds" => [
                "employee_login_logout.employee_id = {$employeeId}",
                "employee_login_logout.work_date = '{$currentDay}'",
            ],
            "return_obj" => "yes",
        ]);
    }

    public static function getLoginLogoutById($id)
    {

        return self::getData([
            "free_conds" => [
                "employee_login_logout.id = '{$id}'",
            ],
            "return_obj" => "yes",
        ]);
    }

    public static function updateLoginLogoutData($loginLogoutId, $data)
    {
        self::where('id','=', $loginLogoutId)->update($data);

    }

    public static function updateLoginLogoutDataByEmployeesIds($employeesIds, $day, $data)
    {
        self::where('work_date', '=', $day)->
            whereIn('employee_id', $employeesIds)->
            update($data);
    }

    public static function getLoginLogoutTblOfByEmployeeIdAndSpecificTime($employeeId, $dateFrom, $dateTo)
    {

        return self::getData([
            "free_conds" => [
                "employee_login_logout.employee_id = {$employeeId}",
                "employee_login_logout.work_date >= '{$dateFrom}'",
                "employee_login_logout.work_date <= '{$dateTo}'",
            ],
        ]);
    }
}
