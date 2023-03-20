<?php foreach($fields_name as $key => $value): ?>
    <?php $grid_col = "12"; ?>

    <?php if (isset($grid[$key])): ?>
        <?php $grid_col = $grid[$key]; ?>
    <?php endif;?>

    <div data-hidediv="{{$fields_name[$key]}}_div_id" class="{{$type[$key] == 'hidden' ? "hide_div":""}} {{((strpos($class[$key],"clear_both")!==false)?"clear_both":"")}}  col-md-{{$grid_col}} pull-left form-group {{$fields_name[$key]}}_div_class">

        <div id="{{$fields_name[$key]}}"></div>

        <?php if($type[$key] != 'checkbox' && $type[$key] != 'hidden'):?>
             <label for="{{$fields_name[$key]}}_id">
                 {!! $labels_name[$key] !!}

                 <span class="red_star">
                     {{$required[$key]=="required"?"*":""}}
                 </span>
             </label>
        <?php endif;?>

        <div {{(($type[$key]=="checkbox")?'style="height: 34px;"':"")}} >
            <!-- Case for Type -->
            <?php if($type[$key] == 'textarea'):?>
                 <textarea name="{{$value}}" style="resize:vertical" class="form-control {{string_safe($fields_name[$key])}}_class  {{$class[$key]}}"  {!!$required[$key]!!} id="{{$fields_name[$key]}}_id">{{$values[$key]}}</textarea>
            <?php elseif ($type[$key] == 'number'): ?>

                 <input
                     type="{{$type[$key]}}"
                     class="form-control {{$class[$key]}} {{$fields_name[$key]}}"
                     {!! (strpos($required[$key], 'step') !== false)? $required[$key]:$required[$key].' step=0.0001' !!}
                     name="{{$value}}"
                     value="{{$values[$key]}}"
                     id="{{$fields_name[$key]}}_id"
                 >

            <?php elseif ($type[$key] == 'checkbox'): ?>

                <label class="form-control btm_checkbox_label" for="{{$fields_name[$key]}}_id">
                    <?php
                        $class[$key] = str_replace("form-control", " ", $class[$key]);
                    ?>
                    <input type="{{$type[$key]}}" value="1" class="{{$class[$key]}}" {!!$required[$key]!!} name="{{$value}}" {{(($values[$key]==1)?"checked":"")}} id="{{$fields_name[$key]}}_id">
                    {{$labels_name[$key]}}
                </label>

            <?php elseif ($type[$key] == "date_time"): ?>
                <?php

                    if ($values[$key] != "" && $values[$key] != "0000-00-00 00:00:00") {
                        //$values[$key]=\Carbon\Carbon::now();
                        $values[$key] = date('m / j / Y g:i a', strtotime($values[$key]));
                        if ($values[$key] == "01/1/1970 2:00 am") {
                            $values[$key] = "";
                        }
                    } else {
                        $values[$key] = "";
                    }
                ?>

                <div class='input-group' id='datetimepicker_{{$key}}'>
                    <!-- 01/30/2017 4:29 PM  -->
                    <input type="text" class="form-control {{$class[$key]}} datetimepicker_input_date_time" value="{{$values[$key]}}" {!!$required[$key]!!} name="{{$fields_name[$key]}}" id="{{$fields_name[$key]}}_id"/>

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar la la-calendar"></span>
                        </span>
                    </div>
                </div>

            <?php elseif ($type[$key] == "date"): ?>
                <!-- 01/30/2017 4:29 PM -->
                <div class='input-group date' id='datetimepicker_{{$fields_name[$key]}}'>
                    <?php

                        if ($values[$key] != "" && $values[$key] != "1970-01-01" && $values[$key] != "0000-00-00") {
                            //$values[$key]=\Carbon\Carbon::now();
                            $values[$key] = date('d-m-Y', strtotime($values[$key]));
                        } else {
                            $values[$key] = "";
                        }

                    ?>
                    <input type="text" class="form-control datetimepicker_input_date {{$class[$key]}} {{$fields_name[$key]}} " value="{{$values[$key]}}" {!!$required[$key]!!} name="{{$fields_name[$key]}}" id="{{$fields_name[$key]}}_id"/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar la la-calendar"></span>
                        </span>
                    </div>
                 </div>

            <?php elseif ($type[$key] == "time"): ?>
                <div class='input-group date' id='datetimepicker_{{$fields_name[$key]}}'>
                    <?php
                        //01/30/2017 4:29 PM

                        if ($values[$key] != "") {
                            $values[$key] = date('H:i', strtotime($values[$key]));
                        } else {
                            $values[$key] = "";
                        }
                    ?>

                    <input type="text" class="form-control {{$class[$key]}}  datetimepicker_input_time " value="{{$values[$key]}}" {!!$required[$key]!!} name="{{$fields_name[$key]}}" id="{{$fields_name[$key]}}_id" />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar la la-calendar"></span>
                        </span>
                    </div>
                </div>

            <?php elseif ($type[$key] == "month_year"): ?>
                <div class='input-group' id='datetimepicker_{{$fields_name[$key]}}'>
                    <input type="text" class="form-control {{$class[$key]}}  datetimepicker_input_month_year" value="{{$values[$key]}}" {!!$required[$key]!!}  name="{{$fields_name[$key]}}" id="{{$fields_name[$key]}}_id" />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar la la-calendar"></span>
                        </span>
                    </div>
                </div>

            <?php else: ?>
                <input
                        type="{{$type[$key]}}"
                        class="form-control {{$class[$key]}} {{$fields_name[$key]}} "
                        {!!$required[$key]!!}
                        name="{{$fields_name[$key]}}"
                        value="{{$values[$key]}}"
                        id="{{$fields_name[$key]}}_id"
                >
            <?php endif;?>
        </div>
    </div>

<?php endforeach; ?>
