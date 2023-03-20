<?php

function dump_date($str_data="",$format="Y-m-d h:i A"){
    if(empty($str_data))return "";

    return date($format,strtotime($str_data));
}

function diff_two_dates($date1,$date2=""){

    if($date2 == ""){
        $date2 = date("Y-m-d");
    }

    $date1 = strtotime($date1);
    $date2 = strtotime($date2);

    $diff = $date1 - $date2;

    return round($diff / (60 * 60 * 24));

}

function diffTwoDatesInYears($date1,$date2=""){

    if($date2 == ""){
        $date2 = date("Y-m-d");
    }

    $diff = abs(strtotime($date2) - strtotime($date1));
    return floor($diff / (365*60*60*24));

}

function splitMinutesIntoHoursAndMinutes($minutes = 0)
{

    $arr = [
        "hours"   => 0,
        "minutes" => 0,
    ];

    if ($minutes > 0) {

        $get_hours    = intval(($minutes / 60));
        $arr["hours"] = $get_hours;

        $arr["minutes"] = (
            $minutes - ($get_hours * 60)
        );

    }

    return $arr;
}
