<?php

if(isset($data->{$field_name})){
    $selected_value=$data->{$field_name};
}

if(!is_array($selected_value)){
    $selected_value=[$selected_value];
}
?>

<div class="generate_radio_btns {{$grid}} form-group" {{$custom_style}} >

    <?php if(!$hide_label):?>
    <p for="">{{ $label_name }}</p>
    <?php endif;?>

    <?php foreach($values as $key => $value): ?>

        <?php
        $selected="";
        if (in_array($value,$selected_value)) {
            $selected="checked";
        }

        if($input_type=="radio"&&$key==0&&$selected_value==""){
            $selected="checked";
        }
        ?>

        <div class="{{$item_grid}}">
            <label class="form-control">
                <input class="{{$class}}" name="{{$field_name.$multiple}}" type="{{$input_type}}" value="{{$values[$key]}}" {{$selected}} <?=$additional_data?> >{{capitalize_string($text[$key])}}
            </label>
        </div>
    <?php endforeach; ?>
</div>


