<?php

namespace App\models\hr;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;

class hr_paycheck_m extends \Eloquent
{
    use SoftDeletes;

    protected $table = "hr_paycheck";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'employee_id', 'p_year', 'p_month', 'p_weeks',
        'p_should_work_hours', 'p_total_worked_hours',
        'p_amount', 'p_is_received'
    ];

    static function getData($attrs)
    {

        $results = self::select(\DB::raw("
            hr_paycheck.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }



    public static function getPaychecksOfEmployeeByYearAndMonth($employeeId, $year, $month)
    {


        return self::getData([
            "free_conds" => [
                "employee_id = $employeeId",
                "p_year = $year",
                "p_month = $month"
            ],

        ]);

    }


    public static function addPaycheck($data)
    {
        return self::create($data);
    }


    public static function updatePaycheckIsReceived($paycheckId, $status)
    {
        return self::where('id', '=', $paycheckId)->
        update(array(
            "p_is_received" => $status,
        ));

    }

}
