<?php

/**
 *
 * @var $results \Illuminate\Support\Collection
 * @var $employee_data object
 */

 $notPaidWeeksIndexes = [];

?>

<div class="slim-mainpanel">
    <div class="container-fluid">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item"><a href="{{url("admin/dashboard")}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$header}}</li>
            </ol>
            <h6 class="slim-pagetitle">{{$header}}</h6>
        </div><!-- slim-pageheader -->


        <div class="section-wrapper mb-3">
            <form id="save_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p class="mg-b-20 mg-sm-b-20"></p>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Month</label>
                                    <select class="form-control" name="month">
                                        <option value="0"></option>

                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}">{{ $i }}</option>
                                        @endfor
                                    </select>


                                </div>


                            </div>
                            <?php
                                echo generate_select_years("", '2022', "form-control", 'year', 'Year', "", "col-md-6")
                            ?>

                            {{csrf_field()}}
                            <div class="col-md-12">
                                <button id="submit" type="submit" class="btn btn-primary bd-0 mt-0 btn-search-date">Get Results</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="section-wrapper mb-3">
            <label class="section-title">Paychecks</label>
            <p class="mg-b-20 mg-sm-b-40"></p>
            <div class="row">

                <?php
                    $weeksNames = [
                        '1' => 'First Week',
                        '2' => 'Second Week',
                        '3' => 'Third Week',
                        '4' => 'Fourth Week',
                        '5' => 'Fifth Week',
                        '6' => 'sixth Week',
                        '7' => 'Seventh Week',
                    ];

                    $isReceivedStatus = [
                        1 => "Yes",
                        0 => "No"
                    ]

                ?>

                @if(count($paychecks) > 0):
                    <table class="table display">
                        <thead>
                            <tr>
                                <th class="wd-10p">&nbsp;</th>
                                <th class="wd-10p">Should Work Hours</th>
                                <th class="wd-10p">Total Worked Hours</th>
                                <th class="wd-10p">Amount</th>
                                <th class="wd-10p">Is Received</th>
                            </tr>
                        </thead>
                        @foreach($paychecks as $paycheck)
                            <tr>
                                <td>
                                    <?php
                                        $weekIndexes = explode(',', $paycheck->p_weeks);
                                        $lastKey =  array_key_last($weekIndexes);
                                    ?>
                                    @foreach($weekIndexes as $key => $weekIndex)
                                        {{ $weeksNames[$weekIndex] }}

                                        @if($key != array_key_last($weekIndexes))
                                            &
                                        @endif

                                    @endforeach
                                </td>
                                <td>{{ $paycheck->p_should_work_hours }}</td>
                                <td>{{ $paycheck->p_total_worked_hours }}</td>
                                <td>{{ $paycheck->p_amount }}</td>
                                <td>
                                    <?php if($paycheck->p_is_received == 0 && havePermission("admin/paycheck", "change_is_received")): ?>
                                        <?php
                                            echo generate_multi_accepters(
                                                $accepturl = url("admin/paycheck/change-is-received"),
                                                $item_obj = $paycheck,
                                                $item_primary_col = "id",
                                                $accept_or_refuse_col = "is_received",
                                                $model = 'App\models\hr\hr_paycheck_m',
                                                $accepters_data = [
                                                    "1" => '<i>Yes</i>',
                                                    "0" => '<i>No</i>',
                                                ],
                                                $display_block = false,
                                                $func_after_edit = ""
                                            );
                                        ?>
                                    <?php else:?>
                                        <label>{{ $isReceivedStatus[$paycheck->p_is_received] }}</label>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    @include('global_components.no_results_found')
                @endif
            </div>

        </div>

        <div class="section-wrapper mb-3">

            <form id="save_form" enctype="multipart/form-data">
                <div class="row">

                    @if($add_login_logout === true)

                        <div class="col-md-3">
                            <?php

                                if (!empty($current_day_login_logout->login_logout_times)){
                                    $loginLogoutTimes    = json_decode($current_day_login_logout->login_logout_times, true);
                                    $lastLoginLogoutTime =  $loginLogoutTimes[array_key_last($loginLogoutTimes)];
                                }
                            ?>
                            @if(isset($lastLoginLogoutTime) && !isset($lastLoginLogoutTime['logout']))
                                <a class="btn btn-primary mg-b-6" href="{{url("admin/employee-hr/employee-login-logout/logout")}}">
                                    Check Out
                                </a>
                            @else
                                <a class="btn btn-primary mg-b-6" href="{{url("admin/employee-hr/employee-login-logout/login")}}">
                                    Check In
                                </a>
                            @endif
                        </div>
                    @endif

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Total Should Work Time : </label>
                            <span>{{ $total_should_work_hours }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Total Work Time : </label>
                            <span>{{ $total_working_hours }}</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-40"></p>

            <?php if(is_array($results->all()) && count($results->all())): ?>


                <div class="table-wrapper">


                    <table class="table display">
                        <thead>
                        <tr>
                            <th class="wd-10p"><span>Day</span></th>
                            <th class="wd-5p"><span>Day Name</span></th>
                            <th class="wd-5p"><span>Login</span></th>
                            <th class="wd-5p"><span>Logout</span></th>
                            <th class="wd-5p"><span>Should Work Hours</span></th>
                            <th class="wd-5p"><span>Working Hours</span></th>
                            <th class="wd-5p"><span>Remain Hours</span></th>
                            <th class="wd-5p"><span>Overtime Hours</span></th>
                            <th class="wd-5p"><span>Late Hours</span></th>
                            <th class="wd-5p"><span>Early Leave Hours</span></th>
                            <th class="wd-5p"><span>Is Holiday</span></th>
                            <th class="wd-5p"><span>Has Early Leave</span></th>
                            <th class="wd-5p"><span>Has Delay Request</span></th>

                        </tr>
                        </thead>
                        <tbody>

                            <?php
                                $worksDayForEmployee = json_decode($employee_data->employee_working_days, true);
                                $weekIndex = 0;
                                $paidWeeks = $paychecks->pluck('p_weeks')->all();

                                $allPaidWeeksIndexes = [];
                                foreach ($paidWeeks as $key => $week){
                                    $allPaidWeeksIndexes = array_merge($allPaidWeeksIndexes, explode(',', $week)) ;
                                }
                            ?>

                            @for($date = $date_from; $date <= $date_to; $date++)

                                <?php
                                    $item = $results->where('work_date', '=', $date)->first();

                                    if (is_null($item)){
                                        continue;
                                    }

                                    if (
                                        $item->is_work_day  == 0 ||
                                        $item->work_day_is_general_holiday  == 1 ||
                                        $item->work_day_is_demanded_holiday == 1
                                    ){
                                        $class = "background-color: #b4ffa7";
                                    }

                                    if (
                                        empty($item->login_logout_times) &&
                                        $item->is_work_day  == 1 &&
                                        $item->work_day_is_general_holiday  != 1 &&
                                        $item->work_day_is_demanded_holiday  != 1
                                    ) {
                                        $class = "background-color: #ff9d9d";
                                    }


                                    if (
                                        !empty($item->login_logout_times) &&
                                        $item->is_work_day  == 1 &&
                                        $item->work_day_is_general_holiday  == 0 &&
                                        $item->work_day_is_demanded_holiday  == 0
                                    ){
                                        $class = "";
                                    }
                                ?>

                                @if(
                                    strtolower(date('l', strtotime($date))) === strtolower($branch_data->first_day_of_the_week) &&
                                    $date != $date_from
                                )

                                    <?php
                                        $weekIndex +=1;
                                        $total_hours = calculateTotalHoursPerWeek(
                                            $date_from,
                                            $date_to,
                                            $date,
                                            $results,
                                            $branch_data->first_day_of_the_week
                                        );

                                        $_GET["test"] = "123";

                                        $totalWorkingHoursAfterOverTime = calculateTotalWorkHoursAfterOvertimeHours(
                                            $total_hours['work_hours_per_week'],
                                            $total_hours['overtime_hours_per_week'],
                                            $employee_data->employee_overtime_hour_rate
                                        )

                                    ?>

                                    <tr id="{{$weekIndex}}">
                                        <td colspan="3">
                                            <label style="color: black">Should Work Time Per Week : </label>
                                            <span>{{ calculateTotalTime($total_hours['should_work_hours_per_week']) }}</span>
                                        </td>

                                        <td colspan="3">
                                            <label style="color: black">Work Time Per Week : </label>
                                            <span>
                                                {{
                                                    $totalWorkingHoursAfterOverTime
                                                }}
                                            </span>
                                        </td>

                                        <td colspan="3">
                                            @if(
                                                    !in_array($weekIndex, $allPaidWeeksIndexes) &&
                                                    intval($totalWorkingHoursAfterOverTime) != 0 &&
                                                    havePermission("admin/paycheck", "add_action")
                                                )
                                                <?php
                                                    $notPaidWeeksIndexes[] = $weekIndex;
                                                    $weeksIndexes          = implode(',', $notPaidWeeksIndexes);
                                                ?>
                                                <a class="btn btn-primary mg-b-6" href="{{url("admin/paycheck/add-paycheck/{$employee_data->employee_id}/$weeksIndexes?year=$year&month=$month")}}">
                                                    Paycheck
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                <tr style="{{$class}}" class="day_row" id="{{$item->id}}">
                                    <td>{{ $date }}</td>
                                    <td>{{ date('l', strtotime($date)) }}</td>

                                    <?php
                                        $loginTimes = [];
                                        $logoutTimes = [];

                                        if (!empty($item->login_logout_times)){
                                            $loginLogout = json_decode($item->login_logout_times, true);

                                            foreach ($loginLogout as $key => $time){
                                                $loginTimes[$key]  = !isset($time['login']) ? "" : $time['login'];
                                                $logoutTimes[$key] = !isset($time['logout']) ? "" : $time['logout'];
                                            }

                                        }


                                    ?>


                                    <td>

                                        <?php
                                            $currentWeekIndex = $weekIndex + 1;
                                        ?>
                                        @if(!empty($loginTimes))


                                            @foreach($loginTimes as $key => $time)

                                                @if(havePermission('admin/employee_login_logout','edit_action') && !in_array($currentWeekIndex, $allPaidWeeksIndexes))
                                                    <input type="time" class="edit_login_logout" name="{{ $key }}" data-old_value="{{$time}}" value="{{$time}}" data-row_id="{{$item->id}}" data-time_type="login">
                                                @else
                                                    {{$time}}
                                                @endif

                                                <br>
                                            @endforeach
                                        @else

                                            @if(havePermission('admin/employee_login_logout','edit_action') && !in_array($currentWeekIndex, $allPaidWeeksIndexes))
                                                <span class="add_login_logout" data-value="login"><i class="fa fa-edit"></i></span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($logoutTimes))
                                            @foreach($logoutTimes as $key => $time)

                                                @if(havePermission('admin/employee_login_logout','edit_action') && !in_array($currentWeekIndex, $allPaidWeeksIndexes))
                                                    <input type="time" class="edit_login_logout" name="{{ $key }}" value="{{$time}}" data-row_id="{{$item->id}}" data-time_type="logout">
                                                @else
                                                    {{$time}}
                                                @endif

                                                <br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ date('H:i', strtotime($item->should_work_hours)) }}</td>
                                    <td>{{ date('H:i', strtotime($item->working_hours)) }}</td>
                                    <td>{{ date('H:i', strtotime($item->remain_hours)) }}</td>
                                    <td>{{ date('H:i', strtotime($item->overtime_hours)) }}</td>
                                    <td>{{ date('H:i', strtotime($item->late_time_hours)) }}</td>
                                    <td>{{ date('H:i', strtotime($item->early_leave_hours)) }}</td>
                                    <td>
                                        {{$item->work_day_is_general_holiday == 1  ? "General Holiday" : ""}}
                                        {{$item->work_day_is_demanded_holiday == 1  ? "Demanded Holiday" : ""}}
                                    </td>
                                    <td>{{ $item->work_day_has_early_leave   == 1 ? "Yes" : "No" }}</td>
                                    <td>{{ $item->work_day_has_delay_request == 1 ? "Yes" : "No" }}</td>

                                </tr>

                                @if($date == $date_to)

                                    <?php
                                        $total_hours = calculateTotalHoursPerWeek($date_from, $date, $date, $results, $branch_data->first_day_of_the_week);
                                        $weekIndex +=1;

                                        $totalWorkingHoursAfterOverTime = calculateTotalWorkHoursAfterOvertimeHours(
                                            $total_hours['work_hours_per_week'],
                                            $total_hours['overtime_hours_per_week'],
                                            $employee_data->employee_overtime_hour_rate
                                        )
                                    ?>

                                    <tr id="{{$weekIndex}}">
                                        <td colspan="3">
                                            <label style="color: black">Should Work Time Per Week : </label>
                                            <span>{{ calculateTotalTime($total_hours['should_work_hours_per_week']) }}</span>
                                        </td>
                                        <td colspan="3">
                                            <label style="color: black">Work Time Per Week : </label>
                                            <span>
                                                {{
                                                   $totalWorkingHoursAfterOverTime
                                                }}
                                            </span>
                                        </td>
                                        <td colspan="3">

                                            @if(!in_array($weekIndex, $allPaidWeeksIndexes) && intval($totalWorkingHoursAfterOverTime) != 0)
                                                <?php
                                                    $notPaidWeeksIndexes[] = $weekIndex;
                                                    $weeksIndexes          = implode(',', $notPaidWeeksIndexes);
                                                ?>
                                                <a class="btn btn-primary mg-b-6" href="{{url("admin/paycheck/add-paycheck/{$employee_data->employee_id}/$weeksIndexes?year=$year&month=$month")}}">
                                                    Paycheck
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                            @endfor
                        </tbody>
                    </table>

                </div><!-- table-wrapper -->
            <?php else : ?>
                @include('global_components.no_results_found')
            <?php endif; ?>
        </div><!-- section-wrapper -->

    </div><!-- container -->
</div><!-- slim-mainpanel -->
