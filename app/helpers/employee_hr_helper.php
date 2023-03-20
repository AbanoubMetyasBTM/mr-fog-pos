<?php


function calculateTotalTime($times, $hourRate = 1, $returnAsMinutes=false)
{

    $totalMinutes   = [];
    foreach ($times as $key => $time){
        $minutesOfHours[$key] = date('H', strtotime($time)) * 60;
        $totalMinutes[$key]   = date('i', strtotime($time)) + $minutesOfHours[$key];
    }

    $totalMinutes = array_sum($totalMinutes) * $hourRate;
    if ($returnAsMinutes) {
        return $totalMinutes;
    }

    return returnMinutesAsHoursFormat($totalMinutes);

}

function returnMinutesAsHoursFormat($totalMinutes) : string
{

    $hours        = floor($totalMinutes / 60);
    $minutes      = floor($totalMinutes - ($hours * 60));

    return $hours . ":" . $minutes;

}



function calculateTotalHoursPerWeek($dateFrom, $dateTo, $dayBetweenDateFromAndDateTo, $daysData, $firstDayOfTheWeek):array
{
    $should_work_hours_per_week = [];
    $work_hours_per_week        = [];
    $overtime_hours_per_week    = [];

    if ($dayBetweenDateFromAndDateTo == $dateTo) {
        $lastDayOfWeek = $dayBetweenDateFromAndDateTo;
    }
    else{
        $lastDayOfWeek = date('Y-m-d', strtotime('-1 day', strtotime($dayBetweenDateFromAndDateTo)));
    }


    for( $day = $lastDayOfWeek; $day >= $dateFrom; $day =  date('Y-m-d', strtotime('-1 day', strtotime($day))) ) {

        $dayData                      = $daysData->where('work_date', '=', $day)->first();
        $should_work_hours_per_week[] = date('H:i:s', strtotime($dayData->should_work_hours));
        $work_hours_per_week[]        = date('H:i:s', strtotime($dayData->working_hours));
        $overtime_hours_per_week[]    = date('H:i:s', strtotime($dayData->overtime_hours));

        if(strtolower(date('l', strtotime($day))) == strtolower($firstDayOfTheWeek)) {
            break;
        }
    }


    return [
        'should_work_hours_per_week' => $should_work_hours_per_week,
        'work_hours_per_week'        => $work_hours_per_week,
        'overtime_hours_per_week'    => $overtime_hours_per_week,
    ];
}


function calculateTotalWorkHoursAfterOvertimeHours($workHoursTimes, $overTimeHoursTimes, $overTimeRate):string
{
    $workHours                   = calculateTotalTime($workHoursTimes, 1, true);
    $overTimeHoursWithNormalRate = calculateTotalTime($overTimeHoursTimes, 1, true);
    $overTimeHours               = $overTimeHoursWithNormalRate*$overTimeRate;

    $workHours     = returnMinutesAsHoursFormat($workHours - $overTimeHoursWithNormalRate);
    $overTimeHours = returnMinutesAsHoursFormat($overTimeHours);


    $workHours           = explode(':', $workHours);
    $overTimeHours       = explode(':', $overTimeHours);

    $totalHoursInMinutes = ($workHours[0] * 60) + ($overTimeHours[0] * 60) + $workHours[1] + $overTimeHours[1];
    $totalHours          = floor($totalHoursInMinutes / 60);
    $totalMinutes        = floor($totalHoursInMinutes - ($totalHours * 60));


    return $totalHours . ":" . $totalMinutes;
}

