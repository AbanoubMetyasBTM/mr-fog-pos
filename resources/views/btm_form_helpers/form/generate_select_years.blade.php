
<?php

if(is_object($data)&&isset($data->{$name})){
    $already_selected_value=$data->{$name};
}

?>

<div class='{{$grid}}'>
    <label for="">{{$label}}</label>
    <div>
        <select class="form-control {{$class}}" style="cursor: pointer" name="{{$name}}" >
            <?php foreach(range(date('Y'), $earliest_year)  as $key =>$x): ?>
                <?php if($key==0):?>
                    <option value='0'></option>
                <?php endif;?>
                <option value="{{$x}}" {{$x == $already_selected_value ? ' selected="selected"' : '' }}>{{$x}}</option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
