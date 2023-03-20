<?php

if(is_object($data_obj)&&isset($data_obj->{$field_name_1})){
    $field_selected_value_1=$data_obj->{$field_name_1};
}

if(is_object($data_obj)&&isset($data_obj->{$field_name_2})){
    $field_selected_value_2=$data_obj->{$field_name_2};
}

$field_id_1=$field_name_1."_id";
$field_id_2=$field_name_2."_id";
$field_div_container_2=$field_name_2."_div_container";

?>
<div class="col-md-12 form-group  the_big_parent_for_depended_selects">
    <div class="row">
        <div class="col-md-6">
            <label style="width: 100%;">{{$field_label_1}}</label>
            <select id="{{$field_id_1}}" name="{{$field_name_1}}" class=" parent_depended_selects  {{$field_class_1}}" {{$field_required_1}}>
                <option value=""></option>
                <?php foreach($field_values_1 as $key => $value): ?>
                    <?php
                        $selected_value_1="";
                        if ($value==$field_selected_value_1) {
                            $selected_value_1="selected";
                        }

                        $additional_attr="";
                        if (isset($field_data_values1[$key])) {
                            $additional_attr="data-$field_data_name1='".$field_data_values1[$key]."'";
                        }

                    ?>

                        <option {{$additional_attr}} value="{{$field_values_1[$key]}}" {{$selected_value_1}} >{{$field_text_1[$key]}}</option>
                <?php endforeach; ?>
            </select>
        </div>


        <?php

            //second select
            $display_none_select_2="";


            if ($field_selected_value_2=="") {
                $display_none_select_2="display:none;";
            }
        ?>

        <div class="col-md-6 div_child_depended_selects {{$field_div_container_2}}" style="{{$display_none_select_2}}" >
            <label style="width: 100%;">{{$field_label_2}}</label>
            <select id="{{$field_id_2}}" name="{{$field_name_2}}" class=" child_depended_selects {{$field_class_2}}" {{$field_required_2}}>
                <option value="" data-targetid="0" ></option>

                <?php foreach($field_values_2 as $key => $value): ?>

                    <?php
                        $selected_option="";
                        $hide_option="style='display:none;'";
                        if ($field_2_depend_values[$key]==$field_selected_value_1) {
                            $hide_option="";
                        }
                        if ($value==$field_selected_value_2) {
                            $selected_option="selected";
                        }

                        $additional_attr_sec="";
                        if (isset($field_data_values2[$key])) {
                            $additional_attr_sec="data-$field_data_name2='".$field_data_values2[$key]."'";
                        }
                    ?>
                <option {{$additional_attr_sec}} value="{{$field_values_2[$key]}}" data-targetid="{{$field_2_depend_values[$key]}}" {{$selected_option}} {{$hide_option}} >{{$field_text_2[$key]}}</option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
