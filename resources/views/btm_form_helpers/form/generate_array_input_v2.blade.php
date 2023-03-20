<?php

    if($data!=""){
        $data = collect($data);
        foreach ($field_name as $field_key => $field_value) {
            $values[$field_key]=$data->pluck($field_value)->all();
        }
    }

    if(count($default_values)==0){
        foreach ($field_name as $key => $value) {
            $default_values[]="";
        }
    }
?>


<div class='col-md-12 contain_arr_input'>
    <div class="form-group">

        <?php if(false): ?>
            <label for="">{{capitalize_string($label_name[0])}} Section </label>
        <?php endif; ?>

        <?php if(isset($values[0])&&is_array($values[0])&&count($values[0])): ?>
            <?php foreach($values[0] as $value_key => $item_values): ?>

                <div class="row old_item" style="margin: 10px;padding-bottom: 5px;border-bottom: 2px solid #000;">
                    <?php foreach($field_name as $field_key => $filed): ?>
                        <?php
                            if(!isset($values[$field_key][$value_key])){
                            continue;
                            }
                        ?>

                        <div class="form-group col-md-{{$grid[$field_key]??12}}">
                            <label for="">{!! $label_name[$field_key] !!}</label>

                            <?php if($type[$field_key]!="textarea"): ?>
                                <?php
                                    $item_val="";
                                    if(isset($values[$field_key][$value_key])){
                                        $item_val=$values[$field_key][$value_key];
                                    }
                                ?>

                                <input
                                        type="{{$type[$field_key]}}"
                                        class="{{$class[$field_key]}}"
                                        name="{{$field_name[$field_key]}}[]"
                                        value="{{$item_val}}"
                                >
                            <?php else: ?>

                                <textarea
                                        class="{{$class[$field_key]}}"
                                        style="resize:vertical"
                                        name="{{$field_name[$field_key]}}[]">{!! $values[$field_key][$value_key] !!}</textarea>
                            <?php endif; ?>
                        </div>


                    <?php endforeach; ?>

                    <button type="button" class="btn btn-danger remove_item_class">Delete</button>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>


        <div class="contain_items_class">
            <div class="row first_item_class" style="margin: 10px;">
                <?php foreach($field_name as $key => $single_field): ?>
                    <div class="form-group col-md-{{$grid[$key]??12}}">
                        <label for="">{!! $label_name[$key] !!}</label>
                        <?php if($type[$key]!="textarea"): ?>
                            <input
                                type="{{$type[$key]}}"
                                class="{{$class[$key]}}"
                                value="{{$default_values[$key]}}"
                                name="{{$field_name[$key]}}[]"
                            >
                        <?php else: ?>
                            <textarea
                                    id="tinymcetest"
                                    class="{{$class[$key]}}"
                                    style="resize:vertical"
                                    name="{{$field_name[$key]}}[]"></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="button" data-newid="{{is_array($values[0])?(count($values[0])+1):"1"}}" class="btn btn-warning add_item_class">Add</button>
    </div>
</div>


